<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SimproinWbp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ProgramPembinaan;
use App\Models\WbpPembinaan;

class HaloMinnaApiController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_akses' => ['required', 'string', 'max:20'],
        ]);

        $kodeAkses = strtoupper(trim($validated['kode_akses']));
        $kodeAksesHash = hash('sha256', $kodeAkses);

        $kodeDicabut = DB::table('riwayat_kode_akses')
            ->where('kode_akses_hash', $kodeAksesHash)
            ->exists();

        if ($kodeDicabut) {
            return response()->json([
                'success' => false,
                'message' => 'Kode akses lama sudah tidak bisa digunakan. Silakan gunakan kode akses yang baru.',
            ], 401);
        }

        $user = SimproinWbp::with('wbp')
            ->where('kode_akses', $kodeAkses)
            ->first();

        if (
            !$user ||
            !$user->wbp ||
            $user->wbp->status_wbp !== 'AKTIF' ||
            $user->status_aktif !== 'Aktif'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Kode akses tidak valid atau akun tidak aktif.',
            ], 401);
        }


        $token = $user->createToken('halo-minna-android');

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token_type' => 'Bearer',
            'token' => $token->plainTextToken,
            'data' => $this->profilWbp($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('wbp');

        if (
            !$user->wbp ||
            $user->wbp->status_wbp !== 'AKTIF' ||
            $user->status_aktif !== 'Aktif'
        ) {
            $user->currentAccessToken()?->delete();

            return response()->json([
                'success' => false,
                'message' => 'Akun tidak lagi aktif.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $this->profilWbp($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function programPembinaan()
    {
        $programs = ProgramPembinaan::query()
            ->where('is_active', true)
            ->orderBy('kategori')
            ->orderBy('nama_program')
            ->get([
                'id',
                'kategori',
                'nama_program',
            ]);

        return response()->json([
            'success' => true,
            'data' => $programs,
        ]);
    }

    public function programDiikuti(Request $request)
    {
        $user = $request->user();

        $user->load('wbp');

        if (
            !$user->wbp ||
            $user->wbp->status_wbp !== 'AKTIF' ||
            $user->status_aktif !== 'Aktif'
        ) {
            $user->currentAccessToken()?->delete();

            return response()->json([
                'success' => false,
                'message' => 'Akun atau data WBP tidak aktif.',
            ], 403);
        }

        $programs = WbpPembinaan::query()
            ->where('wbp_id', $user->wbp_id)
            ->with([
                'program:id,kategori,nama_program',
            ])
            ->orderByDesc('tanggal_mulai')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'program_id' => $item->program_pembinaan_id,
                    'kategori' => $item->program?->kategori,
                    'nama_program' => $item->program?->nama_program,
                    'tanggal_mulai' => $item->tanggal_mulai?->format('Y-m-d'),
                    'tanggal_selesai' => $item->tanggal_selesai?->format('Y-m-d'),
                    'indikator_keaktifan' => $item->indikator_keaktifan,
                    'status_keikutsertaan' => $item->status_keikutsertaan,
                    'keterangan' => $item->keterangan,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $programs,
        ]);
    }

    public function statistikPembinaan(Request $request)
    {
        $user = $request->user();

        $user->load('wbp');

        if (
            !$user->wbp ||
            $user->wbp->status_wbp !== 'AKTIF' ||
            $user->status_aktif !== 'Aktif'
        ) {
            $user->currentAccessToken()?->delete();

            return response()->json([
                'success' => false,
                'message' => 'Akun atau data WBP tidak aktif.',
            ], 403);
        }

        // Ambil seluruh data keikutsertaan pembinaan dari semua WBP aktif.
        $riwayat = WbpPembinaan::query()
            ->whereHas('wbp', function ($query) {
                $query->where('status_wbp', 'AKTIF');
            })
            ->with('program:id,kategori,nama_program')
            ->get();

        $aktif = $riwayat->where('status_keikutsertaan', 'Aktif');
        $selesai = $riwayat->where('status_keikutsertaan', 'Selesai');

        return response()->json([
            'success' => true,
            'data' => [
                'total_diikuti' => $riwayat
                    ->pluck('wbp_id')
                    ->unique()
                    ->count(),

                'total_aktif' => $aktif
                    ->pluck('wbp_id')
                    ->unique()
                    ->count(),

                'total_selesai' => $selesai
                    ->pluck('wbp_id')
                    ->unique()
                    ->count(),

                'per_program' => $riwayat
                    ->groupBy('program_pembinaan_id')
                    ->map(function ($items) {
                        $program = $items->first()->program;

                        return [
                            'program_id' => $program?->id,
                            'kategori' => $program?->kategori,
                            'nama_program' => $program?->nama_program,
                            'jumlah_wbp' => $items
                                ->pluck('wbp_id')
                                ->unique()
                                ->count(),
                            'total_aktif' => $items
                                ->where('status_keikutsertaan', 'Aktif')
                                ->pluck('wbp_id')
                                ->unique()
                                ->count(),
                            'total_selesai' => $items
                                ->where('status_keikutsertaan', 'Selesai')
                                ->pluck('wbp_id')
                                ->unique()
                                ->count(),
                        ];
                    })
                    ->values(),
            ],
        ]);
    }

    private function profilWbp(SimproinWbp $user): array
    {
        $wbp = $user->wbp;

        // Prioritas status: Batal > Aktif > Tidak Aktif.
        $registerF = strtoupper(trim((string) $user->register_f));

        if ($registerF === 'YA') {
            $statusBebas = 'Batal';
        } elseif (!empty($user->tgl_pengambilan_berkas)) {
            $statusBebas = 'Aktif';
        } else {
            $statusBebas = 'Tidak Aktif';
        }

        return [
            'nama' => $wbp->nama,
            'no_reg_instansi' => $wbp->no_reg_instansi,
            'putusan' => $wbp->putusan,
            'putusan_bulan' => $wbp->putusan_bulan,
            'masa_1_2' => $this->formatTanggal($wbp->masa_1_2),
            'masa_2_3' => $this->formatTanggal($wbp->masa_2_3),

            'integrasi' => [
                'register_f' => $user->register_f,
                'jenis_usulan' => $user->jenis_usulan,
                'tgl_pengambilan_berkas' => $this->formatTanggal($user->tgl_pengambilan_berkas),
                'tgl_pengembalian_berkas' => $this->formatTanggal($user->tgl_pengembalian_berkas),
                'tgl_permintaan_litmas' => $this->formatTanggal($user->tgl_permintaan_litmas),
                'tgl_litmas_bapas' => $this->formatTanggal($user->tgl_litmas_bapas),
                'tgl_hasil_litmas' => $this->formatTanggal($user->tgl_hasil_litmas),
                'tgl_sidang_tpp' => $this->formatTanggal($user->tgl_sidang_tpp),
                'tgl_input_sdp' => $this->formatTanggal($user->tgl_input_sdp),
                'tgl_verifikasi_ditjen' => $this->formatTanggal($user->tgl_verifikasi_ditjen),
                'tgl_persetujuan_menteri' => $this->formatTanggal($user->tgl_persetujuan_menteri),
                'tgl_otorisasi_sk' => $this->formatTanggal($user->tgl_otorisasi_sk),
                'tgl_pelaksanaan' => $this->formatTanggal($user->tgl_pelaksanaan),
                'tgl_pembebasan' => $this->formatTanggal($user->tgl_pembebasan),
                'status_program' => $statusBebas,
            ],
        ];
    }

    private function formatTanggal(mixed $tanggal): ?string
    {
        if (blank($tanggal)) {
            return null;
        }

        return Carbon::parse($tanggal)->toDateString();
    }
}
