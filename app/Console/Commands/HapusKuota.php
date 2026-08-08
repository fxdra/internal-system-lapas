<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Antrian;

class HapusKuota extends Command
{
    protected $signature = 'antrian:hapus-kuota';
    protected $description = 'Tutup semua kuota yang tanggalnya sudah lewat, termasuk hari ini jam 00:00';

    public function handle()
    {
        // Waktu sekarang
        $now = Carbon::now('Asia/Jakarta');

        // Ambil semua record yang tanggal < hari ini
        $kuotaLalu = Antrian::whereDate('tanggal', '<', $now->toDateString())->get();

        // Jika ada, tutup
        foreach ($kuotaLalu as $antrian) {
            $antrian->update([
                'kuota' => 0,
                'keterangan' => 'DITUTUP (Tanggal sudah lewat)',
                'updated_at' => now(),
            ]);
            $this->info("Kuota tanggal {$antrian->tanggal} ditutup.");
        }

        // Tambahan: tutup kuota hari ini jika sudah lewat jam 23:59 (misal)
        $today = Antrian::whereDate('tanggal', $now->toDateString())->first();
        if ($today && $now->hour >= 0) { // otomatis berlaku setiap hari setelah jam 00:00
            $today->update([
                'kuota' => 0,
                'keterangan' => 'DITUTUP (Hari ini sudah berakhir)',
                'updated_at' => now(),
            ]);
            $this->info("Kuota hari ini ({$today->tanggal}) ditutup.");
        }

        $this->info("Proses hapus/tutup kuota selesai.");
        return Command::SUCCESS;
    }
}
