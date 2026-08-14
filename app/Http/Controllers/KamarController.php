<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Wbp;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\KamarService;

class KamarController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $kamar = Kamar::when($search, function ($q) use ($search) {

            // Khusus keyword "isolasi"
            if (strtolower($search) === 'isolasi') {

                $q->where('lokasi_blok', 'SEL ISOLASI');

                return;
            }

            $q->where(function ($qq) use ($search) {

                $qq->where('kode_blok', 'like', "%{$search}%")
                    ->orWhere('lokasi_sel', 'like', "%{$search}%")
                    ->orWhere('kode_kamar', 'like', "%{$search}%");

                if (preg_match('/^([A-H])\s*([0-9]+)$/i', $search, $m)) {

                    $qq->orWhere(function ($x) use ($m) {

                        $x->where('kode_blok', strtoupper($m[1]))
                            ->where('lokasi_sel', 'like', '%' . $m[2] . '%');
                    });
                }
            });
        })
            ->get();

        /*
    |--------------------------------------------------------------------------
    | SORT BERDASARKAN KODE BLOK + NOMOR KAMAR
    |--------------------------------------------------------------------------
    */

        $kamar = $kamar->sort(function ($a, $b) {

            /*
        |--------------------------------------------------------------------------
        | SORT KODE BLOK (A, B, C, D, ...)
        |--------------------------------------------------------------------------
        */

            $kode = strcmp($a->kode_blok, $b->kode_blok);

            if ($kode !== 0) {
                return $kode;
            }

            /*
        |--------------------------------------------------------------------------
        | SORT NATURAL NOMOR KAMAR
        |--------------------------------------------------------------------------
        */

            preg_match('/(\d+)/', $a->lokasi_sel, $ma);
            preg_match('/(\d+)/', $b->lokasi_sel, $mb);

            $aNo = (int) ($ma[1] ?? 0);
            $bNo = (int) ($mb[1] ?? 0);

            return $aNo <=> $bNo;
        })->values();

        /*
        |--------------------------------------------------------------------------
        | SEMUA KAMAR UNTUK DROPDOWN
        |--------------------------------------------------------------------------
        */

        $kamarUntukSelect = Kamar::orderBy('kode_blok')
            ->orderBy('lokasi_sel')
            ->get()
            ->sort(function ($a, $b) {

                /*
        |--------------------------------------------------------------------------
        | SORT BERDASARKAN KODE BLOK
        |--------------------------------------------------------------------------
        */

                $kode = strcmp(
                    $a->kode_blok,
                    $b->kode_blok
                );

                if ($kode !== 0) {
                    return $kode;
                }

                /*
        |--------------------------------------------------------------------------
        | SORT NATURAL NOMOR KAMAR
        |--------------------------------------------------------------------------
        */

                preg_match(
                    '/(\d+)/',
                    $a->lokasi_sel ?? '',
                    $ma
                );

                preg_match(
                    '/(\d+)/',
                    $b->lokasi_sel ?? '',
                    $mb
                );

                $aNo = (int) (
                    $ma[1] ?? 0
                );

                $bNo = (int) (
                    $mb[1] ?? 0
                );

                return $aNo <=> $bNo;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $perPage = 10;

        $page = LengthAwarePaginator::resolveCurrentPage();

        $kamar = new LengthAwarePaginator(

            $kamar->forPage($page, $perPage)->values(),

            $kamar->count(),

            $perPage,

            $page,

            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]

        );

        return view(
            'admin-banceuy.kamar',
            compact(
                'kamar',
                'kamarUntukSelect'
            )
        );
    }

    public function print()
    {
        $data = Kamar::all()

            ->sort(function ($a, $b) {

                $kode = strcmp(
                    $a->kode_blok ?? '',
                    $b->kode_blok ?? ''
                );

                if ($kode !== 0) {
                    return $kode;
                }

                preg_match('/(\d+)/', $a->lokasi_sel ?? '', $ma);
                preg_match('/(\d+)/', $b->lokasi_sel ?? '', $mb);

                $aNo = (int) ($ma[1] ?? 0);
                $bNo = (int) ($mb[1] ?? 0);

                return $aNo <=> $bNo;
            })

            ->groupBy('kode_blok')

            ->map(function ($items) {

                return $items->map(function ($kamar) {

                    $room = KamarService::buildRoom($kamar);
                    preg_match('/(\d+)/', $kamar->lokasi_sel ?? '', $match);
                    $no = (int) ($match[1] ?? 0);

                    return (object)[

                        'kamar_id'    => $kamar->id,
                        'kode_kamar'  => $kamar->kode_kamar,

                        'lokasi_blok' => $room->lokasi_blok,
                        'lokasi_sel'  => $room->lokasi_sel,

                        'nama_kamar'  => $room->nama_kamar,
                        'no_kamar'    => $room->no_kamar,

                        'img_barcode' => $kamar->img_barcode,


                    ];
                })->values();
            });

        return view('admin-banceuy.kamar-print', [
            'groups' => $data
        ]);
    }

    // ================= SEARCH AJAX =================
    public function search(Request $request)
    {
        $query = trim($request->query('query'));

        $kamars = Kamar::with('wbps')
            ->when($query, function ($q) use ($query) {
                $q->where('kode_blok', 'like', "%{$query}%")
                    ->orWhere('lokasi_sel', 'like', "%{$query}%")
                    ->orWhere('kode_kamar', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();

        $results = $kamars->map(function ($kamar) {

            $room = KamarService::buildRoom($kamar);

            return [
                'id' => $room->kamar_id,
                'text' => strtoupper($room->lokasi_blok) . ' - ' . $room->lokasi_sel,
            ];
        });

        return response()->json($results);
    }

    public function detail($id)
    {
        $data = Kamar::findOrFail($id);

        return response()->json([
            'nama_kamar' => $data->nama_kamar,
            'kode_kamar' => $data->kode_kamar,
            'blok' => 'BLOK ' . $data->kode_blok,
            'sel' => $data->lokasi_sel,
            'status' => $data->status_kamar,
            'barcode' => $data->barcode_id,
            'img_barcode' => $data->img_barcode
        ]);
    }

    public function updateStatusKamar(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'status_kamar' => 'required|in:Terbuka,Tertutup'
        ]);

        DB::beginTransaction();

        try {
            $kamar = Kamar::findOrFail($request->kamar_id);

            $kamar->update([
                'status_kamar' => $request->status_kamar
            ]);

            $affected = Wbp::where('kamar_id', $request->kamar_id)
                ->update([
                    'status_kamar' => $request->status_kamar
                ]);

            DB::commit();

            return response()->json([
                'message' => 'Berhasil update',
                'kamar_id' => $kamar->id,
                'status' => $kamar->status_kamar,
                'wbp_terupdate' => $affected
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal update',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadPdf(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'file_pdf' => 'required|mimes:pdf|max:5120'
        ]);

        $kamar = Kamar::findOrFail($request->kamar_id);

        // ================= HAPUS FILE LAMA =================
        if ($kamar->pdf_file && \Storage::disk('public')->exists($kamar->pdf_file)) {
            \Storage::disk('public')->delete($kamar->pdf_file);
        }

        // ================= SIMPAN FILE BARU =================
        $file = $request->file('file_pdf');

        $fileName = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs(
            'kamar_file',
            $fileName,
            'public'
        );

        // ================= UPDATE DB =================
        $kamar->update([
            'pdf_file' => $path
        ]);

        return response()->json([
            'message' => 'PDF berhasil diupload & file lama diganti',
            'file' => $fileName
        ]);
    }

    public function updateBarcodeUrl()
    {
        $kamars = Kamar::all();

        foreach ($kamars as $kamar) {

            $kamar->url_barcode =
                url('/scan/' . $kamar->barcode_id);

            $kamar->save();
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function show($uuid)
    {
        $kamar = Kamar::with([
            'wbps' => function ($query) {

                $query->where('status_wbp', 'AKTIF')
                    ->orderBy('nama');
            }
        ])
            ->where('barcode_id', $uuid)
            ->firstOrFail();

        $setting = Setting::first();

        return view('detail-kamar', compact('kamar', 'setting'));
    }

    public function getKamar()
    {
        try {

            $kamars = Kamar::with([
                'wbps' => function ($query) {
                    $query->where('status_wbp', 'AKTIF')
                        ->orderBy('nama')
                        ->select([
                            'nama',
                            'negara',
                            'agama',
                            'jenis_kejahatan',
                            'putusan',
                            'ekspirasi',
                            'lokasi_blok',
                            'lokasi_sel',
                            'status_kamar',
                            'status_wbp',
                            'kamar_id'
                        ]);
                }
            ])
                ->orderBy('lokasi_blok', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $kamars
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function kamarWithWbps()
    {
        $kamars = Kamar::with([

            'wbps' => function ($q) {

                $q->where('status_wbp', 'AKTIF')
                    ->select([
                        'id',
                        'nama',
                        'no_reg_instansi',
                        'agama',
                        'jenis_kejahatan',
                        'putusan',
                        'ekspirasi',
                        'lokasi_blok',
                        'lokasi_sel',
                        'status_wbp',
                        'kamar_id',
                        'foto_wbp'
                    ])
                    ->orderBy('nama');
            }

        ])
            ->orderBy('lokasi_blok', 'asc')
            ->orderBy('lokasi_sel', 'asc')
            ->get();

        // ======================================================
        // WBP LUAR TEMBOK
        // ======================================================

        $wbpRs = Wbp::where('status_wbp', 'SAKIT')
            ->select([
                'id',
                'nama',
                'no_reg_instansi',
                'agama',
                'jenis_kejahatan',
                'putusan',
                'ekspirasi',
                'lokasi_blok',
                'lokasi_sel',
                'status_wbp',
                'kamar_id',
                'foto_wbp'
            ])
            ->orderBy('nama')
            ->get();

        $wbpBon = Wbp::where('status_wbp', 'BON')
            ->select([
                'id',
                'nama',
                'no_reg_instansi',
                'agama',
                'jenis_kejahatan',
                'putusan',
                'ekspirasi',
                'lokasi_blok',
                'lokasi_sel',
                'status_wbp',
                'kamar_id',
                'foto_wbp'
            ])
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TRANSFORM PERSIS MODEL SNAPSHOT
        |--------------------------------------------------------------------------
        */
        $data = $kamars->map(function ($kamar) {

            $room = KamarService::buildRoom($kamar);
            $kapasitas = $room->kapasitas;
            $jumlahWbp = $kamar->wbps->count();
            $persen = $kapasitas > 0
                ? ($jumlahWbp / $kapasitas) * 100
                : 0;

            return (object) [

                'kamar_id'        => $kamar->id,
                'kode_kamar'      => $kamar->kode_kamar,
                'nama_kamar'      => $room->nama_kamar,
                'lokasi_blok'     => $room->lokasi_blok,
                'lokasi_sel'      => $room->lokasi_sel,
                'no_kamar'        => $room->no_kamar,
                'nama_blok'       => $kamar->kode_blok,
                'judul_kamar'     => strtoupper($room->lokasi_blok) . ' - ' . $room->lokasi_sel,
                'kode_blok'       => $kamar->kode_blok,
                'status_kamar'    => $kamar->status_kamar,

                // =========================
                // KAPASITAS
                // =========================
                'kapasitas'       => $kapasitas,
                'jumlah_wbp'      => $jumlahWbp,
                'persen_kapasitas' => $persen,
                'badge_kapasitas' => $persen >= 100
                    ? 'bg-danger'
                    : (
                        $persen >= 80
                        ? 'bg-warning text-dark'
                        : 'bg-success'
                    ),

                'wbps' => $kamar->wbps->map(function ($wbp) {

                    return [

                        'id' => $wbp->id,
                        'nama' => $wbp->nama,
                        'no_reg_instansi' => $wbp->no_reg_instansi,
                        'agama' => $wbp->agama,
                        'jenis_kejahatan' => $wbp->jenis_kejahatan,
                        'putusan' => $wbp->putusan,
                        'ekspirasi' => $wbp->ekspirasi,
                        'lokasi_blok' => $wbp->lokasi_blok,
                        'lokasi_sel' => $wbp->lokasi_sel,
                        'status_wbp' => $wbp->status_wbp,
                        'kamar_id' => $wbp->kamar_id,
                        'foto_wbp' => $wbp->foto_wbp,

                    ];
                })->values()

            ];
        });

        /*
        |--------------------------------------------------------------------------
        | SORT NATURAL PERSIS SNAPSHOT
        |--------------------------------------------------------------------------
        */

        $data = $data->sort(function ($a, $b) {

            // Urutkan berdasarkan kode blok
            if ($a->kode_blok !== $b->kode_blok) {

                return strcmp(
                    $a->kode_blok ?? '',
                    $b->kode_blok ?? ''
                );
            }


            // Ambil nomor kamar dari lokasi_sel
            preg_match('/(\d+)/', $a->lokasi_sel ?? '', $ma);
            preg_match('/(\d+)/', $b->lokasi_sel ?? '', $mb);


            $aNo = (int) ($ma[1] ?? 0);
            $bNo = (int) ($mb[1] ?? 0);


            return $aNo <=> $bNo;
        })->values();

        $grouped = $data
            ->groupBy('kode_blok')
            ->sortKeys();

        return view(
            'admin-banceuy.data-kamar-print',
            compact(
                'grouped',
                'wbpRs',
                'wbpBon'
            )
        );
    }
}
