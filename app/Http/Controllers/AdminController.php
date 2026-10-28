<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Admin, Antrian, Kritik, Pengunjung, Wbp, Kamar, Mutasi};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\KamarService;
use App\Models\WbpIsolasiHistory;


class AdminController extends Controller
{
    public function loginView()
    {
        if (session()->has('admin')) {
            return redirect('/admin-banceuy');
        }

        return view('admin-banceuy.login-view');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin-login')
            ->with('success', 'Berhasil logout');
    }

    public function submitFormAdmin(Request $request)
    {
        $request->validate([
            'nip'      => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('nip', $request->nip)->first();

        if (
            $admin &&
            Hash::check($request->password, $admin->password)
        ) {

            Auth::guard('admin')->login($admin);

            $request->session()->regenerate();

            return redirect('/admin-banceuy')
                ->with('success', 'Login berhasil.');
        }

        return redirect()->back()
            ->withErrors([
                'nip' => 'NIP atau password tidak sesuai.',
            ])
            ->withInput();
    }

    public function index(Request $request)
    {
        // ===================== TOTAL WBP AKTIF =====================
        $totalWbpAktif = Wbp::where('status_wbp', 'AKTIF')->count();
        $totalNarapidanaAktif = Wbp::where('status_wbp', 'AKTIF')
            ->where('klasifikasi_wbp', 'NARAPIDANA')
            ->count();
        $totalTahananAktif = Wbp::where('status_wbp', 'AKTIF')
            ->where('klasifikasi_wbp', 'TAHANAN')
            ->count();

        // ===================== WBP LUAR TEMBOK =====================
        $totalWbpLuarTembok = Wbp::whereIn('status_wbp', [
            'BON',
            'SAKIT',
        ])->count();
        $totalWbpBon = Wbp::where('status_wbp', 'BON')
            ->count();

        $totalWbpSakit = Wbp::where('status_wbp', 'SAKIT')
            ->count();


        // ===================== JENIS KEJAHATAN =====================
        $byJenisKejahatan = Wbp::select(
            'jenis_kejahatan',
            DB::raw('COUNT(*) as total')
        )
            ->whereNotNull('jenis_kejahatan')
            ->groupBy('jenis_kejahatan')
            ->orderByDesc('total')
            ->get();

        // ===================== DATA KAMAR =====================
        $totalKamar = Kamar::count();

        $totalKamarTerbuka = Kamar::where('status_kamar', 'Terbuka')->count();

        $totalKamarTertutup = Kamar::where('status_kamar', 'Tertutup')->count();

        // ===================== BREAKDOWN KAMAR TERTUTUP =====================.

        $kamarTertutup = Kamar::where('status_kamar', 'Tertutup')
            ->orderBy('kode_blok')
            ->orderBy('lokasi_sel')
            ->get();

        $kamarBerisi = Kamar::whereHas('wbpsAktif')
            ->orderBy('kode_blok')
            ->orderBy('lokasi_sel')
            ->get();

        $kamarTertutupBreakdown = collect([

            // ===================== MAPENALING =====================
            [
                'nama' => 'MAPENALING',
                'kamar' => $kamarBerisi
                    ->filter(function ($kamar) {

                        if (strtoupper($kamar->kode_blok ?? '') !== 'B') {
                            return false;
                        }

                        preg_match(
                            '/(\d+)/',
                            $kamar->lokasi_sel ?? '',
                            $match
                        );

                        $nomorKamar = (int) ($match[1] ?? 0);

                        return $nomorKamar >= 1
                            && $nomorKamar <= 6;
                    })
                    ->values(),
            ],

            // ===================== MAXIMUM =====================
            [
                'nama' => 'MAXIMUM',
                'kamar' => $kamarTertutup
                    ->filter(function ($kamar) {

                        if (strtoupper($kamar->kode_blok ?? '') !== 'B') {
                            return false;
                        }

                        preg_match(
                            '/(\d+)/',
                            $kamar->lokasi_sel ?? '',
                            $match
                        );

                        $nomorKamar = (int) ($match[1] ?? 0);

                        return $nomorKamar >= 7
                            && $nomorKamar <= 12;
                    })
                    ->sortBy(function ($kamar) {
                        preg_match(
                            '/(\d+)/',
                            $kamar->lokasi_sel ?? '',
                            $match
                        );

                        return (int) ($match[1] ?? 0);
                    })
                    ->values(),
            ],

            // ===================== SEL ISOLASI =====================
            [
                'nama' => 'SEL ISOLASI',
                'kamar' => $kamarTertutup
                    ->filter(function ($kamar) {
                        return strtoupper($kamar->kode_blok ?? '') === 'ISOLASI';
                    })
                    ->sortBy(function ($kamar) {
                        preg_match(
                            '/(\d+)/',
                            $kamar->lokasi_sel ?? '',
                            $match
                        );

                        return (int) ($match[1] ?? 0);
                    })
                    ->values(),
            ],

        ])->filter(function ($item) {

            return $item['kamar']->isNotEmpty();
        })->values();

        // ===================== RINGKASAN HUNIAN =====================

        $kamarsHunian = Kamar::with([
            'wbps' => function ($query) {
                $query->where('status_wbp', 'AKTIF');
            }
        ])->get();

        $kamarData = KamarService::buildData($kamarsHunian);
        $kamarGrouped = KamarService::buildGrouped($kamarData);
        $summaryHunian = KamarService::buildSummary($kamarGrouped);

        $summaryBlok = $summaryHunian
            ->reject(function ($item) {
                return in_array($item['blok'], [
                    'DAPUR',
                    'RUMAH SAKIT',
                    'SEL ISOLASI',
                ]);
            })
            ->values();

        $summaryUnitKhusus = $summaryHunian
            ->filter(function ($item) {
                return in_array($item['blok'], [
                    'DAPUR',
                    'RUMAH SAKIT',
                    'SEL ISOLASI',
                ]);
            })
            ->values();

        $selMaksimumPending = WbpIsolasiHistory::query()
            ->with(['wbp', 'kamar'])
            ->whereNull('tgl_keluar_isolasi')
            ->whereNull('diverifikasi_pada')
            ->whereHas('kamar', function ($query) {
                $query->where('kode_blok', 'B')
                    ->whereRaw(
                        'CAST(REGEXP_SUBSTR(lokasi_sel, "[0-9]+") AS UNSIGNED) BETWEEN 7 AND 12'
                    );
            })
            ->whereRaw(
                'DATE_ADD(tgl_masuk_isolasi, INTERVAL 14 DAY) <= ?',
                [now()]
            )
            ->get();

        // ===================== PERHATIAN OPERASIONAL =====================
        $operationalAlerts = KamarService::buildOperationalAlerts(
            $summaryHunian,
            $totalWbpBon,
            $totalWbpSakit,
            $selMaksimumPending->count()
        );

        // ===================== FILTER =====================
        $filter  = $request->input('filter');
        $tanggal = $request->input('tanggal');

        // ===================== DAFTAR BLOK DISPLAY =====================
        $blokList = collect([
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'DAPUR',
            'SEL ISOLASI',
        ]);

        // ===================== QUERY MUTASI =====================
        $queryMutasi = Mutasi::with('kamarAsal');

        // ===================== FILTER TANGGAL =====================
        if ($filter == 'today') {

            $queryMutasi->whereDate('created_at', today());
        } elseif ($filter == 'yesterday') {

            $queryMutasi->whereDate('created_at', today()->subDay());
        } elseif ($filter == 'date' && !empty($tanggal)) {

            $queryMutasi->whereDate('created_at', $tanggal);
        }

        // ===================== AMBIL DATA =====================
        $mutasis = $queryMutasi->get();

        // ===================== TOTAL MUTASI =====================
        $totalMutasi = $mutasis->count();

        // ===================== HITUNG MUTASI PER BLOK =====================
        $countMutasiPerBlok = $blokList->map(function ($blok) use ($mutasis) {

            $total = $mutasis->filter(function ($mutasi) use ($blok) {

                $kamarAsal = $mutasi->kamarAsal;

                if (!$kamarAsal) {
                    return false;
                }

                return strtoupper($kamarAsal->kode_blok ?? '') === strtoupper($blok);
            })->count();

            return (object) [
                'lokasi_blok' => in_array($blok, range('A', 'H'))
                    ? "Blok {$blok}"
                    : $blok,

                'total_mutasi' => $total,
            ];
        });

        return view('admin-banceuy.dashboard', compact(

            // ===================== TOTAL =====================
            'totalWbpAktif',
            'totalNarapidanaAktif',
            'totalTahananAktif',
            'totalWbpLuarTembok',
            'totalWbpBon',
            'totalWbpSakit',

            // ===================== CHART =====================
            'byJenisKejahatan',

            // ===================== RINGKASAN HUNIAN =====================
            'summaryBlok',
            'summaryUnitKhusus',

            // ===================== PERHATIAN OPERASIONAL =====================
            'operationalAlerts',

            // ===================== KAMAR =====================
            'totalKamar',
            'totalKamarTerbuka',
            'totalKamarTertutup',
            'kamarTertutupBreakdown',

            // ===================== MUTASI =====================
            'totalMutasi',
            'countMutasiPerBlok',

            // ===================== FILTER =====================
            'filter',
            'tanggal',

            // ============ VERIFIKASI SEL MAKSIMUM =============
            'selMaksimumPending',
        ));
    }

    // Notification
    public function getNotifications()
    {
        $pengunjungs = Pengunjung::orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_pengunjung' => $item->nama_pengunjung,
                    'nama_wbp' => $item->nama_wbp,
                    'foto_ktp' => $item->foto_ktp,
                    'created_at' => $item->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'total' => $pengunjungs->count(),
            'pengunjungs' => $pengunjungs
        ]);
    }

    public function verifikasiMassalSelMaksimum(Request $request)
    {
        $petugas = Auth::guard('admin')->user();
        $petugasId = $petugas?->id;
        $petugasRole = $petugas?->role;

        if (!$petugasId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi petugas tidak ditemukan.',
            ], 401);
        }

        $sekarang = now();

        $jumlah = DB::transaction(function () use ($petugasId, $petugasRole, $sekarang) {
            $riwayat = WbpIsolasiHistory::query()
                ->whereNull('tgl_keluar_isolasi')
                ->whereNull('diverifikasi_pada')
                ->whereRaw(
                    'DATE_ADD(tgl_masuk_isolasi, INTERVAL 14 DAY) <= ?',
                    [$sekarang]
                )
                ->lockForUpdate()
                ->get();

            foreach ($riwayat as $item) {
                $item->diverifikasi_pada = $sekarang;
                $item->diverifikasi_oleh = $petugasId;
                $item->diverifikasi_role = $petugasRole;
                $item->save();
            }

            return $riwayat->count();
        });

        return response()->json([
            'success' => true,
            'message' => "{$jumlah} WBP berhasil diverifikasi.",
            'jumlah' => $jumlah,
        ]);
    }
}
