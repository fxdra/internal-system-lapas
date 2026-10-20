<?php

namespace App\Http\Controllers;

use App\Models\SimproinWbp;
use App\Models\Wbp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class HaloMinnaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $wbpId = $request->query('wbp_id');

        $wbps = Wbp::query()
            ->where('status_wbp', 'AKTIF')
            ->with('simproin')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_reg_instansi', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();


        $registrasi = null;

        if ($wbpId !== null) {
            $registrasi = SimproinWbp::with('wbp')
                ->where('wbp_id', $wbpId)
                ->first();

            if (!$registrasi) {
                $wbpTerpilih = Wbp::findOrFail($wbpId);
            } else {
                $wbpTerpilih = $registrasi->wbp;
            }
        } else {
            $wbpTerpilih = null;
        }

        return view('admin-banceuy.halo-minna', compact(
            'wbps',
            'search',
            'registrasi',
            'wbpTerpilih'
        ));
    }

    public function simpanBagian(
        Request $request,
        Wbp $wbp,
        string $bagian
    ) {
        $aturan = $this->aturanBagian($bagian);

        $validated = $request->validate($aturan);

        $registrasi = DB::transaction(function () use (
            $wbp,
            $bagian,
            $validated
        ) {
            // Ambil registrasi yang sudah ada atau buat yang baru.
            $registrasi = SimproinWbp::firstOrCreate(
                ['wbp_id' => $wbp->id],
                [
                    'kode_akses' => $this->generateKodeAkses(),
                    'register_f' => 'Tidak',
                    'jenis_usulan' => 'Belum Usul',
                    'status_bebas' => 'Belum Bebas',
                    'status_aktif' => 'Aktif',
                ]
            );

            // Hanya field dari bagian yang dikirim yang diperbarui.
            $registrasi->fill($validated);
            $registrasi->save();

            return $registrasi;
        });

        return redirect()
            ->route('halo-minna.index', array_filter([
                'wbp_id' => $wbp->id,
                'search' => $request->input('search'),
                'page' => $request->input('page'),
            ], fn($value) => $value !== null && $value !== ''))
            ->with('success', 'Bagian form berhasil disimpan.');
    }


    public function generateUlangKode(Request $request, Wbp $wbp)
    {
        $kodeBaru = DB::transaction(function () use ($wbp) {
            $registrasi = SimproinWbp::where('wbp_id', $wbp->id)
                ->lockForUpdate()
                ->firstOrFail();

            $kodeLama = $registrasi->kode_akses;

            if ($kodeLama) {
                DB::table('riwayat_kode_akses')->updateOrInsert(
                    ['simproin_wbp_id' => $registrasi->id],
                    [
                        'kode_akses_hash' => hash('sha256', $kodeLama),
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            // Cabut seluruh token login lama.
            $registrasi->tokens()->delete();

            // Buat kode akses baru.
            $registrasi->kode_akses = $this->generateKodeAkses();
            $registrasi->save();

            return $registrasi->kode_akses;
        });

        return redirect()
            ->route('halo-minna.index', array_filter([
                'wbp_id' => $wbp->id,
                'search' => $request->input('search'),
                'page' => $request->input('page'),
            ], fn($value) => $value !== null && $value !== ''))
            ->with(
                'success',
                'Kode akses berhasil diperbarui. Kode lama dan sesi login sebelumnya sudah tidak berlaku.'
            )
            ->with('kode_akses_baru', $kodeBaru);
    }


    private function aturanBagian(string $bagian): array
    {
        $tanggal = ['nullable', 'date'];

        return match ($bagian) {
            'identitas' => [
                'register_f' => [
                    'required',
                    Rule::in(['Ya', 'Tidak']),
                ],
                'jenis_usulan' => [
                    'required',
                    Rule::in([
                        'Asimilasi',
                        'Cuti Bersyarat',
                        'Cuti Mengunjungi Keluarga',
                        'Cuti Menjelang Bebas',
                        'Pembebasan Bersyarat',
                        'Belum Usul',
                    ]),
                ],
            ],

            'berkas' => [
                'tgl_pengambilan_berkas' => $tanggal,
                'tgl_pengembalian_berkas' => $tanggal,
                'tgl_permintaan_litmas' => $tanggal,
                'tgl_litmas_bapas' => $tanggal,
                'tgl_hasil_litmas' => $tanggal,
                'tgl_sidang_tpp' => $tanggal,
            ],

            'sdp_sk' => [
                'tgl_input_sdp' => $tanggal,
                'tgl_verifikasi_ditjen' => $tanggal,
                'tgl_persetujuan_menteri' => $tanggal,
                'tgl_otorisasi_sk' => $tanggal,
            ],

            'tahap_akhir' => [
                'tgl_pelaksanaan' => $tanggal,
                'tgl_pembebasan' => $tanggal,
                'status_bebas' => [
                    'required',
                    Rule::in([
                        'Belum Bebas',
                        'Sudah Bebas',
                        'Batal',
                    ]),
                ],
            ],

            'penjamin' => [
                'nama_penjamin' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'hubungan_penjamin' => [
                    'nullable',
                    'string',
                    'max:100',
                ],
                'no_hp_penjamin' => [
                    'nullable',
                    'string',
                    'max:20',
                ],
                'alamat_penjamin' => [
                    'nullable',
                    'string',
                ],
            ],

            default => throw ValidationException::withMessages([
                'bagian' => 'Bagian form tidak valid.',
            ]),
        };
    }

    private function generateKodeAkses(): string
    {
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        for ($percobaan = 0; $percobaan < 10; $percobaan++) {
            $kode = 'BNCY-';

            for ($i = 0; $i < 8; $i++) {
                $kode .= $karakter[random_int(0, strlen($karakter) - 1)];
            }

            if (!SimproinWbp::where('kode_akses', $kode)->exists()) {
                return $kode;
            }
        }

        throw new \RuntimeException(
            'Gagal membuat kode akses unik. Silakan coba lagi.'
        );
    }
}
