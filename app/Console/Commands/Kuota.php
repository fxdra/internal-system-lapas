<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Antrian;

class Kuota extends Command
{
    // Nama command artisan
    protected $signature = 'antrian:kuota';
    protected $description = 'Generate kuota 100 untuk semua hari Senin sampai Kamis untuk tanggal berikutnya';

    public function handle()
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        // Tentukan tanggal mulai
        if (in_array($today->dayOfWeekIso, [5, 6, 7])) {
            $startDate = $today->next(Carbon::MONDAY);
        } else {
            $startDate = $today->copy();
        }

        // 🎯 Tentukan Kamis aktif berikutnya
        $endDate = $startDate->copy();
        while ($endDate->dayOfWeekIso != 4) {
            $endDate->addDay();
        }

        // Kalau Kamis itu minggu ini, cari Kamis minggu depan
        if ($endDate->lte($startDate)) {
            $endDate->addWeek()->next(Carbon::THURSDAY);
        }

        // 🔁 Loop dari start sampai Kamis berikutnya
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

            if (in_array($date->dayOfWeekIso, [1, 2, 3, 4])) {
                Antrian::updateOrCreate(
                    ['tanggal' => $date->toDateString()],
                    [
                        'kuota' => 100,
                        'keterangan' => 'HARI BIASA',
                        'updated_at' => now(),
                    ]
                );

                $this->info("AKTIF: {$date->translatedFormat('l, d-m-Y')}");
            } else {
                Antrian::updateOrCreate(
                    ['tanggal' => $date->toDateString()],
                    [
                        'kuota' => 0,
                        'keterangan' => 'HARI BIASA',
                        'updated_at' => now(),
                    ]
                );

                $this->info("DITUTUP: {$date->translatedFormat('l, d-m-Y')}");
            }
        }

        $this->info('Update kuota sampai Kamis aktif berikutnya selesai.');
        return Command::SUCCESS;
    }
}
