<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Kuota;
use App\Console\Commands\HapusKuota;

class Kernel extends ConsoleKernel
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

    // Load command dari folder Commands dan routes/console.php
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
