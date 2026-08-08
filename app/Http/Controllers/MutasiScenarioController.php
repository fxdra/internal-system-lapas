<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\MutasiSimulasiService;
use App\Models\Kamar;
use App\Models\Admin;
use App\Models\Wbp;
use App\Models\MutasiScenario;
use App\Models\Mutasi;
use Carbon\Carbon;

class MutasiScenarioController extends Controller
{
    protected $service;

    public function __construct(MutasiSimulasiService $service)
    {
        $this->service = $service;
    }

    public function simulate(Request $request)
    {
        Log::info('SIMULATE REQUEST', $request->all());

        try {

            // =====================================================
            // VALIDASI BARU (NO scenario_id)
            // =====================================================
            $data = $request->validate([

                'tanggal' =>
                'required|date',

                'nama_simulasi' =>
                'required|string',

                'items' =>
                'required|array|min:1',

                'items.*.wbp_id' =>
                'required',

                'items.*.kamar_asal_id' =>
                'required',

                'items.*.kamar_tujuan_id' =>
                'required',
            ]);

            $result = $this->service->simulate($data);

            return response()->json($result);
        } catch (\Throwable $e) {

            Log::error('SIMULATE ERROR', [
                'msg' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function snapshot(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'filter' => 'nullable|in:today,yesterday,custom',
            'selected_date' => 'nullable|date_format:Y-m-d',
        ]);

        $filter = $request->filter ?? 'today';

        switch ($filter) {

            case 'yesterday':

                $startDate = Carbon::yesterday()->startOfDay();
                $endDate   = Carbon::yesterday()->endOfDay();

                break;

            case 'custom':

                if (!$request->selected_date) {

                    return back()->with(
                        'error',
                        'Tanggal wajib dipilih'
                    );
                }

                $startDate =
                    Carbon::parse(
                        $request->selected_date
                    )->startOfDay();

                $endDate =
                    Carbon::parse(
                        $request->selected_date
                    )->endOfDay();

                break;

            default:

                $startDate =
                    Carbon::today()
                    ->startOfDay();

                $endDate =
                    Carbon::today()
                    ->endOfDay();

                break;
        }



        /*
        |--------------------------------------------------------------------------
        | KAMAR (TIDAK DIUBAH)
        |--------------------------------------------------------------------------
        */

        $kamars =
            Kamar::orderBy(
                'lokasi_blok'
            )
            ->orderBy(
                'lokasi_sel'
            )
            ->get();



        /*
        |--------------------------------------------------------------------------
        | KONDISI KAMAR SAAT INI
        | -> SESUDAH
        |--------------------------------------------------------------------------
        */

        $currentPerKamar =
            Wbp::where(
                'status_wbp',
                'aktif'
            )
            ->selectRaw(
                '
            kamar_id,
            COUNT(*) total
            '
            )
            ->groupBy(
                'kamar_id'
            )
            ->pluck(
                'total',
                'kamar_id'
            );



        /*
        |--------------------------------------------------------------------------
        | MUTASI SESUAI TANGGAL
        |--------------------------------------------------------------------------
        */

        $mutasis =
            DB::table(
                'mutasis'
            )
            ->whereBetween(
                'created_at',
                [
                    $startDate,
                    $endDate
                ]
            )
            ->orderBy(
                'created_at'
            )
            ->get();



        /*
        |--------------------------------------------------------------------------
        | ANTI DOUBLE COUNT
        |--------------------------------------------------------------------------
        */

        $mutasis =
            $mutasis
            ->groupBy(
                function (
                    $row
                ) {

                    return
                        $row->wbp_id
                        . '|'
                        .
                        Carbon::parse(
                            $row->created_at
                        )
                        ->toDateString();
                }
            )
            ->map(
                function (
                    $rows
                ) {

                    return
                        $rows
                        ->sortByDesc(
                            'created_at'
                        )
                        ->first();
                }
            )
            ->values();



        /*
        |--------------------------------------------------------------------------
        | HITUNG PERGERAKAN
        |--------------------------------------------------------------------------
        */

        $masuk = [];

        $keluar = [];


        foreach (
            $mutasis
            as $trx
        ) {

            if (
                $trx->kamar_asal_id
            ) {

                $keluar[$trx->kamar_asal_id] =
                    (
                        $keluar[$trx->kamar_asal_id]
                        ??
                        0
                    )
                    + 1;
            }


            if (
                $trx->kamar_tujuan_id
            ) {

                $masuk[$trx->kamar_tujuan_id] =
                    (
                        $masuk[$trx->kamar_tujuan_id]
                        ??
                        0
                    )
                    + 1;
            }
        }



        /*
        |--------------------------------------------------------------------------
        | BUILD DATA
        |--------------------------------------------------------------------------
        */

        $data =
            $kamars
            ->map(
                function (
                    $kamar
                )
                use (
                    $currentPerKamar,
                    $masuk,
                    $keluar
                ) {

                    $sesudah =
                        $currentPerKamar[$kamar->id]
                        ??
                        0;


                    $jMasuk =
                        $masuk[$kamar->id]
                        ??
                        0;


                    $jKeluar =
                        $keluar[$kamar->id]
                        ??
                        0;


                    $sebelum =
                        max(
                            0,
                            (
                                $sesudah
                                -
                                $jMasuk
                                +
                                $jKeluar
                            )
                        );


                    return (object)[

                        'kamar_id'
                        =>
                        $kamar->id,

                        'kode_kamar'
                        =>
                        $kamar->kode_kamar,

                        'lokasi_blok'
                        =>
                        $kamar->lokasi_blok,

                        'lokasi_sel'
                        =>
                        $kamar->lokasi_sel,

                        'no_kamar'
                        =>
                        $this
                            ->extractNoKamar(
                                $kamar
                                    ->lokasi_sel
                            ),

                        'nama_kamar'
                        =>
                        $kamar->nama_kamar,

                        'sebelum'
                        =>
                        $sebelum,

                        'masuk'
                        =>
                        $jMasuk,

                        'keluar'
                        =>
                        $jKeluar,

                        'sesudah'
                        =>
                        $sesudah,

                        'created_at'
                        =>
                        null,

                    ];
                }
            );


        /*
    |--------------------------------------------------------------------------
    | SORT NATURAL
    |--------------------------------------------------------------------------
    */

        $data =
            $data
            ->sort(
                function (
                    $a,
                    $b
                ) {

                    $kamarA =
                        Kamar::find(
                            $a->kamar_id
                        );

                    $kamarB =
                        Kamar::find(
                            $b->kamar_id
                        );

                    /*
                |--------------------------------------------------------------------------
                | URUTKAN BERDASARKAN BLOK
                |--------------------------------------------------------------------------
                */

                    if (
                        $kamarA->kode_blok
                        !==
                        $kamarB->kode_blok
                    ) {

                        return strcmp(
                            $kamarA->kode_blok,
                            $kamarB->kode_blok
                        );
                    }

                    /*
                |--------------------------------------------------------------------------
                | AMBIL NOMOR KAMAR
                |--------------------------------------------------------------------------
                */

                    preg_match(
                        '/(\d+)/',
                        $a->lokasi_sel,
                        $ma
                    );

                    preg_match(
                        '/(\d+)/',
                        $b->lokasi_sel,
                        $mb
                    );

                    $aNo =
                        (int)(
                            $ma[1]
                            ??
                            0
                        );

                    $bNo =
                        (int)(
                            $mb[1]
                            ??
                            0
                        );

                    return
                        $aNo
                        <=>
                        $bNo;
                }
            )
            ->values();



        /*
    |--------------------------------------------------------------------------
    | GROUP
    |--------------------------------------------------------------------------
    */

        $grouped =
            $data
            ->groupBy(
                function (
                    $item
                ) {

                    return
                        Kamar::find(
                            $item->kamar_id
                        )->kode_blok;
                }
            )
            ->sortKeys();

        /*
    |--------------------------------------------------------------------------
    | GRAND TOTAL PER KAMAR
    |--------------------------------------------------------------------------
    */

        $grand = [

            'sebelum' =>
            $data->sum(
                'sebelum'
            ),

            'masuk' =>
            $data->sum(
                'masuk'
            ),

            'keluar' =>
            $data->sum(
                'keluar'
            ),

            'sesudah' =>
            $data->sum(
                'sesudah'
            ),

        ];



        /*
    |--------------------------------------------------------------------------
    | COUNT STATUS WBP
    |--------------------------------------------------------------------------
    */

        $countAktif =
            Wbp::where(
                'status_wbp',
                'AKTIF'
            )->count();


        $countBon =
            Wbp::where(
                'status_wbp',
                'BON'
            )->count();


        $countSakit =
            Wbp::where(
                'status_wbp',
                'SAKIT'
            )->count();


        $countPindah =
            Wbp::where(
                'status_wbp',
                'PINDAH UPT'
            )->count();


        $countPulang =
            Wbp::where(
                'status_wbp',
                'PULANG'
            )->count();


        $countMeninggal =
            Wbp::where(
                'status_wbp',
                'MENINGGAL'
            )->count();



        /*
    |--------------------------------------------------------------------------
    | GRAND TOTAL WBP
    |--------------------------------------------------------------------------
    */

        $grandWbp =

            $countAktif
            +

            $countBon
            +

            $countSakit
            +

            $countPindah
            +

            $countPulang
            +

            $countMeninggal;

        Carbon::setLocale('id');
        $date = Carbon::now()->translatedFormat('d F Y');
        $kplp = Admin::where('role', 'ka. kplp')->first();

        return view(
            'admin-banceuy.snapshot',

            compact(

                'grouped',

                'filter',

                'startDate',

                'endDate',

                'grand',

                'countAktif',

                'countBon',

                'countSakit',

                'countPindah',

                'countPulang',

                'countMeninggal',

                'grandWbp',
                'date',
                'kplp'

            )
        );
    }


    /**
     * 🔥 AMBIL NOMOR KAMAR
     */
    private function extractNoKamar($text)
    {
        preg_match(
            '/\d+\/(\d+)/',
            $text,
            $match
        );

        return $match[1] ?? '-';
    }


    public function snapshotApp(Request $request)
    {
        /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */
        $request->validate([
            'filter' => 'nullable|in:today,yesterday,custom',
            'selected_date' => 'nullable|date_format:Y-m-d',
        ]);

        $filter = $request->filter ?? 'today';

        switch ($filter) {

            case 'yesterday':

                $startDate = Carbon::yesterday()->startOfDay();
                $endDate   = Carbon::yesterday()->endOfDay();

                break;

            case 'custom':

                if (!$request->selected_date) {

                    return back()->with(
                        'error',
                        'Tanggal wajib dipilih'
                    );
                }

                $startDate =
                    Carbon::parse(
                        $request->selected_date
                    )->startOfDay();

                $endDate =
                    Carbon::parse(
                        $request->selected_date
                    )->endOfDay();

                break;

            default:

                $startDate =
                    Carbon::today()
                    ->startOfDay();

                $endDate =
                    Carbon::today()
                    ->endOfDay();

                break;
        }



        /*
            |--------------------------------------------------------------------------
            | KAMAR (TIDAK DIUBAH)
            |--------------------------------------------------------------------------
            */

        $kamars =
            Kamar::orderBy(
                'lokasi_blok'
            )
            ->orderBy(
                'lokasi_sel'
            )
            ->get();



        /*
            |--------------------------------------------------------------------------
            | KONDISI KAMAR SAAT INI
            | -> SESUDAH
            |--------------------------------------------------------------------------
            */

        $currentPerKamar =
            Wbp::where(
                'status_wbp',
                'aktif'
            )
            ->selectRaw(
                '
                kamar_id,
                COUNT(*) total
                '
            )
            ->groupBy(
                'kamar_id'
            )
            ->pluck(
                'total',
                'kamar_id'
            );



        /*
            |--------------------------------------------------------------------------
            | MUTASI SESUAI TANGGAL
            |--------------------------------------------------------------------------
            */

        $mutasis =
            DB::table(
                'mutasis'
            )
            ->whereBetween(
                'created_at',
                [
                    $startDate,
                    $endDate
                ]
            )
            ->orderBy(
                'created_at'
            )
            ->get();



        /*
            |--------------------------------------------------------------------------
            | ANTI DOUBLE COUNT
            |--------------------------------------------------------------------------
            */

        $mutasis =
            $mutasis
            ->groupBy(
                function (
                    $row
                ) {

                    return
                        $row->wbp_id
                        . '|'
                        .
                        Carbon::parse(
                            $row->created_at
                        )
                        ->toDateString();
                }
            )
            ->map(
                function (
                    $rows
                ) {

                    return
                        $rows
                        ->sortByDesc(
                            'created_at'
                        )
                        ->first();
                }
            )
            ->values();



        /*
            |--------------------------------------------------------------------------
            | HITUNG PERGERAKAN
            |--------------------------------------------------------------------------
            */

        $masuk = [];

        $keluar = [];


        foreach (
            $mutasis
            as $trx
        ) {

            if (
                $trx->kamar_asal_id
            ) {

                $keluar[$trx->kamar_asal_id] =
                    (
                        $keluar[$trx->kamar_asal_id]
                        ??
                        0
                    )
                    + 1;
            }


            if (
                $trx->kamar_tujuan_id
            ) {

                $masuk[$trx->kamar_tujuan_id] =
                    (
                        $masuk[$trx->kamar_tujuan_id]
                        ??
                        0
                    )
                    + 1;
            }
        }



        /*
            |--------------------------------------------------------------------------
            | BUILD DATA
            |--------------------------------------------------------------------------
            */

        $data =
            $kamars
            ->map(
                function (
                    $kamar
                )
                use (
                    $currentPerKamar,
                    $masuk,
                    $keluar
                ) {

                    $sesudah =
                        $currentPerKamar[$kamar->id]
                        ??
                        0;


                    $jMasuk =
                        $masuk[$kamar->id]
                        ??
                        0;


                    $jKeluar =
                        $keluar[$kamar->id]
                        ??
                        0;


                    $sebelum =
                        max(
                            0,
                            (
                                $sesudah
                                -
                                $jMasuk
                                +
                                $jKeluar
                            )
                        );


                    return (object)[

                        'kamar_id'
                        =>
                        $kamar->id,

                        'kode_kamar'
                        =>
                        $kamar->kode_kamar,

                        'lokasi_blok'
                        =>
                        $kamar->lokasi_blok,

                        'lokasi_sel'
                        =>
                        $kamar->lokasi_sel,

                        'no_kamar'
                        =>
                        $this
                            ->extractNoKamar(
                                $kamar
                                    ->lokasi_sel
                            ),

                        'nama_kamar'
                        =>
                        $kamar->nama_kamar,

                        'sebelum'
                        =>
                        $sebelum,

                        'masuk'
                        =>
                        $jMasuk,

                        'keluar'
                        =>
                        $jKeluar,

                        'sesudah'
                        =>
                        $sesudah,

                        'created_at'
                        =>
                        null,

                    ];
                }
            );


        /*
        |--------------------------------------------------------------------------
        | SORT NATURAL
        |--------------------------------------------------------------------------
        */

        $data =
            $data
            ->sort(
                function (
                    $a,
                    $b
                ) {

                    $kamarA =
                        Kamar::find(
                            $a->kamar_id
                        );

                    $kamarB =
                        Kamar::find(
                            $b->kamar_id
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | URUTKAN BERDASARKAN BLOK
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $kamarA->kode_blok
                        !==
                        $kamarB->kode_blok
                    ) {

                        return strcmp(
                            $kamarA->kode_blok,
                            $kamarB->kode_blok
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | AMBIL NOMOR KAMAR
                    |--------------------------------------------------------------------------
                    */

                    preg_match(
                        '/(\d+)/',
                        $a->lokasi_sel,
                        $ma
                    );

                    preg_match(
                        '/(\d+)/',
                        $b->lokasi_sel,
                        $mb
                    );

                    $aNo =
                        (int)(
                            $ma[1]
                            ??
                            0
                        );

                    $bNo =
                        (int)(
                            $mb[1]
                            ??
                            0
                        );

                    return
                        $aNo
                        <=>
                        $bNo;
                }
            )
            ->values();



        /*
        |--------------------------------------------------------------------------
        | GROUP
        |--------------------------------------------------------------------------
        */

        $grouped =
            $data
            ->groupBy(
                function (
                    $item
                ) {

                    return
                        Kamar::find(
                            $item->kamar_id
                        )->kode_blok;
                }
            )
            ->sortKeys();

        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL PER KAMAR
        |--------------------------------------------------------------------------
        */

        $grand = [

            'sebelum' =>
            $data->sum(
                'sebelum'
            ),

            'masuk' =>
            $data->sum(
                'masuk'
            ),

            'keluar' =>
            $data->sum(
                'keluar'
            ),

            'sesudah' =>
            $data->sum(
                'sesudah'
            ),

        ];



        /*
        |--------------------------------------------------------------------------
        | COUNT STATUS WBP
        |--------------------------------------------------------------------------
        */

        $countAktif =
            Wbp::where(
                'status_wbp',
                'AKTIF'
            )->count();


        $countBon =
            Wbp::where(
                'status_wbp',
                'BON'
            )->count();


        $countSakit =
            Wbp::where(
                'status_wbp',
                'SAKIT'
            )->count();


        $countPindah =
            Wbp::where(
                'status_wbp',
                'PINDAH UPT'
            )->count();


        $countPulang =
            Wbp::where(
                'status_wbp',
                'PULANG'
            )->count();


        $countMeninggal =
            Wbp::where(
                'status_wbp',
                'MENINGGAL'
            )->count();



        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL WBP
        |--------------------------------------------------------------------------
        */

        $grandWbp =

            $countAktif
            +

            $countBon
            +

            $countSakit
            +

            $countPindah
            +

            $countPulang
            +

            $countMeninggal;

        Carbon::setLocale('id');
        $date = Carbon::now()->translatedFormat('d F Y');
        $kplp = Admin::where('role', 'ka. kplp')->first();

        return response()->json([
            'success' => true,
            'message' => 'Data snapshot berhasil diambil',

            'data' => [
                'grouped' => $grouped,
                'filter' => $filter,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'grand' => $grand,
            ],

            'statistik_wbp' => [
                'aktif' => $countAktif,
                'bon' => $countBon,
                'sakit' => $countSakit,
                'pindah_upt' => $countPindah,
                'pulang' => $countPulang,
                'meninggal' => $countMeninggal,
                'grand_total' => $grandWbp,
            ],

            'tanggal' => $date,

            'kplp' => $kplp,
        ], 200);
    }

    public function reset()
    {
        Mutasi::whereDate('created_at', Carbon::today())->delete();

        return back()->with('success', 'Snapshot berhasil direset.');
    }
}
