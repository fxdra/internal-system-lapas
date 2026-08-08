<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleKPLPMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $admin = Auth::guard('admin')->user();

        // Belum login
        if (!$admin) {
            return redirect('/admin-login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Tidak memiliki role yang diizinkan
        if (!in_array($admin->role, $roles)) {
            return redirect('/admin-banceuy')
                ->with('error', 'Anda tidak memiliki otoritas untuk mengakses halaman tersebut.');
        }

        return $next($request);
    }
}