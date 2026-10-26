<?php

namespace App\Console\Commands;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\Kuota;
use App\Console\Commands\HapusKuota;

class Kernel
{

    protected $commands = [
        Kuota::class,
        HapusKuota::class,
    ];

    // Scheduler
    protected function schedule(Schedule $schedule)
    {
        // Jalankan setiap hari 00:05 WIB
        $schedule->command('antrian:kuota')
            ->dailyAt('00:05')
            ->timezone('Asia/Jakarta');

        $schedule->command('antrian:hapus-kuota')
            ->dailyAt('00:05')
            ->timezone('Asia/Jakarta');
    }
}
