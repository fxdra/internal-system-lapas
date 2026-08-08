<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator; // ✅ WAJIB DITAMBAHKAN

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        URL::forceScheme('https');

        Paginator::useBootstrapFive(); // ✅ lebih bagus untuk Bootstrap 5
    }
}