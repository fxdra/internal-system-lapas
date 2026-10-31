<?php

namespace App\Console\Commands;

use App\Models\SimproinWbp;
use App\Models\Wbp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\HaloMinnaAccessCodeService;
use RuntimeException;

class GenerateHaloMinnaAccessCodes extends Command
{
    protected $signature = 'halo-minna:generate-access-codes';

    protected $description = 'Generate kode akses Halo Minna untuk WBP berstatus AKTIF';

    private int $dibuat = 0;
    private int $kodeDitambahkan = 0;
    private int $dilewati = 0;

    public function handle(): int
    {
        $totalAktif = Wbp::where('status_wbp', 'AKTIF')->count();

        if ($totalAktif === 0) {
            $this->info('Tidak ada WBP berstatus AKTIF.');
            return self::SUCCESS;
        }

        $this->info("Memproses {$totalAktif} WBP berstatus AKTIF...");

        Wbp::where('status_wbp', 'AKTIF')
            ->chunkById(200, function ($wbps) {
                foreach ($wbps as $wbp) {

                    // Ambil kondisi registrasi sebelum diproses.
                    $registrasiSebelum = SimproinWbp::where(
                        'wbp_id',
                        $wbp->id
                    )->first();

                    $recordBaru = !$registrasiSebelum;
                    $kodeSebelumnya = $registrasiSebelum?->kode_akses;

                    // Serahkan proses pembuatan kode ke service.
                    $registrasi = app(HaloMinnaAccessCodeService::class)
                        ->ensureForWbp($wbp);

                    // WBP nonaktif tidak diproses.
                    if (!$registrasi) {
                        $this->dilewati++;
                        continue;
                    }

                    // Hitung record baru.
                    if ($recordBaru) {
                        $this->dibuat++;
                    }

                    // Hitung kode akses yang baru dibuat.
                    if (blank($kodeSebelumnya) && filled($registrasi->kode_akses)) {
                        $this->kodeDitambahkan++;
                    }

                    // Record lama dengan kode akses yang sudah tersedia.
                    if (!$recordBaru && filled($kodeSebelumnya)) {
                        $this->dilewati++;
                    }
                }
            });

        $this->newLine();
        $this->info('Proses generate kode akses selesai.');
        $this->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Record simproin baru', $this->dibuat],
                ['Kode akses dibuat/ditambahkan', $this->kodeDitambahkan],
                ['Data tanpa perubahan', $this->dilewati],
            ]
        );

        return self::SUCCESS;
    }

    private function generateKodeAkses(): string
    {
        // Tidak menggunakan I, O, 0, dan 1 agar mudah dibaca.
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        for ($percobaan = 0; $percobaan < 20; $percobaan++) {
            $kode = 'BNCY-';

            for ($i = 0; $i < 8; $i++) {
                $kode .= $karakter[random_int(0, strlen($karakter) - 1)];
            }

            // Pastikan kode belum digunakan oleh registrasi lain.
            $kodeSudahDipakai = SimproinWbp::where('kode_akses', $kode)
                ->exists();

            // Pastikan kode yang pernah dicabut tidak diterbitkan kembali.
            $kodePernahDicabut = DB::table('riwayat_kode_akses')
                ->where('kode_akses_hash', hash('sha256', $kode))
                ->exists();

            if (!$kodeSudahDipakai && !$kodePernahDicabut) {
                return $kode;
            }
        }

        throw new RuntimeException(
            'Gagal membuat kode akses unik setelah 20 percobaan.'
        );
    }
}
