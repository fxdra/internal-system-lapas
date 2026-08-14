<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        // CSRF exception
        $middleware->validateCsrfTokens(except: [
            'api/update-pengunjung-status',
            'update-pengunjung-status',
        ]);

        // middleware alias
        $middleware->alias([
            'admin'     => \App\Http\Middleware\AdminMiddleware::class,
            'role.kplp' => \App\Http\Middleware\RoleKPLPMiddleware::class,
            'role'      => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })

    // 🔥 INI YANG PENTING UNTUK COMMAND KUSTOM
    ->withCommands([
        App\Console\Commands\DownloadWbpFoto::class,
        App\Console\Commands\UpdateSelMaksimumStatus::class,
    ])

    ->withSchedule(function ($schedule): void {
        $schedule->command('kamar:update-sel-maksimum')
            ->dailyAt('00:01');
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
