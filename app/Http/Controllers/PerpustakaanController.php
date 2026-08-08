<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Setting;

class PerpustakaanController extends Controller
{
    public function index()
    {

        $setting = Setting::first();
        // Ambil semua buku aktif dengan kategori
        $bukus = Buku::with('kategori')
            ->where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('perpustakaan', compact('bukus', 'setting'));
    }
}
