<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class DataProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::latest()->first();
        return view('admin-banceuy.profile_setting', compact('profile'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'sejarah_singkat'      => 'nullable|string|max:5000',
            'struktur_organisasi'  => 'nullable|string|max:5000',
            'visi_misi'            => 'nullable|string|max:5000',
            'tugas_fungsi'         => 'nullable|string|max:5000',
        ]);
    
        // HTML yang diizinkan
        $allowedTags = '<p><a><b><i><strong><em><ul><ol><li><br><h1><h2><h3><h4><h5><h6>';
    
        // Ambil row pertama
        $profile = Profile::first();
    
        if (!$profile) {
            // Jika belum ada row sama sekali, buat baru
            $profile = new Profile();
        }
    
        // Assign field satu per satu dengan sanitasi
        $profile->sejarah_singkat     = trim(strip_tags($validated['sejarah_singkat'] ?? '', $allowedTags));
        $profile->struktur_organisasi = trim(strip_tags($validated['struktur_organisasi'] ?? '', $allowedTags));
        $profile->visi_misi           = trim(strip_tags($validated['visi_misi'] ?? '', $allowedTags));
        $profile->tugas_fungsi        = trim(strip_tags($validated['tugas_fungsi'] ?? '', $allowedTags));
    
        // Simpan data (update atau insert baru jika tabel kosong)
        $profile->save();
    
        return redirect()->back()->with('success', 'Profile berhasil disimpan!');
    }


}
