<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kamar;
use App\Models\Wbp;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class UpdateSelMaksimumStatus extends Command
{
    protected $signature = 'kamar:update-sel-maksimum';

    protected $description = 'Mengatur status Sel Maksimum B-7 sampai B-12 berdasarkan jadwal kunjungan';


    public function handle()
    {
        $hari = now()->dayOfWeekIso;

        // Senin = 1, Kamis = 4
        $hariKunjungan = in_array($hari, [1, 4], true);

        $statusJadwal = $hariKunjungan
            ? 'Terbuka'
            : 'Tertutup';

        $kamars = Kamar::where('kode_blok', 'B')
            ->whereIn('lokasi_sel', [
                'KAMAR 7',
                'KAMAR 8',
                'KAMAR 9',
                'KAMAR 10',
                'KAMAR 11',
                'KAMAR 12',
            ])
            ->get();

        // Status fisik kamar tetap mengikuti jadwal lama.
        foreach ($kamars as $kamar) {
            $kamar->update([
                'status_kamar' => $statusJadwal,
            ]);
        }

        $wbps = Wbp::whereIn('kamar_id', $kamars->pluck('id'))
            ->get();

        // Ambil riwayat semua WBP yang berada di Sel Maksimum.
        $riwayatPerWbp = DB::table('wbp_isolasi_histories')
            ->whereIn('wbp_id', $wbps->pluck('id'))
            ->orderByDesc('tgl_masuk_isolasi')
            ->get()
            ->groupBy('wbp_id');

        $sekarang = now();
        $jumlahTerbuka = 0;
        $jumlahTertutup = 0;

        foreach ($wbps as $wbp) {
            $riwayat = $riwayatPerWbp->get($wbp->id);

            // WBP lama tanpa riwayat tetap memakai jadwal sebelumnya.
            if (!$riwayat || $riwayat->isEmpty()) {
                $statusWbp = $statusJadwal;
            } else {
                // Cari periode isolasi yang masih aktif.
                $periodeAktif = $riwayat->first(
                    fn($item) => $item->tgl_keluar_isolasi === null
                );

                // WBP yang pernah memiliki riwayat tidak otomatis mendapat jadwal lama jika periode aktif tidak ada.
                $bolehTerbuka = $periodeAktif !== null
                    && $periodeAktif->diverifikasi_pada !== null
                    && $hariKunjungan
                    && Carbon::parse(
                        $periodeAktif->tgl_masuk_isolasi
                    )->addDays(14)->lessThanOrEqualTo($sekarang);

                $statusWbp = $bolehTerbuka
                    ? 'Terbuka'
                    : 'Tertutup';
            }

            // Perbarui status WBP secara individual.
            if ($wbp->status_kamar !== $statusWbp) {
                $wbp->status_kamar = $statusWbp;
                $wbp->save();
            }

            if ($statusWbp === 'Terbuka') {
                $jumlahTerbuka++;
            } else {
                $jumlahTertutup++;
            }
        }

        $this->info(
            "Status kamar fisik: {$statusJadwal}. "
                . "WBP terbuka: {$jumlahTerbuka}; "
                . "WBP tertutup: {$jumlahTertutup}."
        );

        return self::SUCCESS;
    }
}
