<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kritik;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KritikController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('kritik', compact('setting'));
    }


    public function store(Request $request)
    {
        // ================== VALIDASI KETAT ==================
        $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
                // hanya huruf, spasi, titik, apostrophe
                'regex:/^[A-Za-z\s\.\']+$/'
            ],
            'jenis' => ['required', Rule::in(['KRITIK', 'SARAN'])],
            'nik'   => ['required', 'digits:16'],
            'foto_ktp' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'pesan' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.regex'    => 'Nama lengkap hanya boleh huruf dan spasi.',
            'jenis.required'        => 'Jenis wajib dipilih.',
            'nik.required'          => 'NIK wajib diisi.',
            'nik.digits'            => 'NIK harus 16 digit angka.',
            'foto_ktp.required'     => 'Foto KTP wajib diupload.',
            'foto_ktp.image'        => 'Foto KTP harus berupa gambar.',
            'foto_ktp.mimes'        => 'Foto KTP hanya boleh JPG/JPEG/PNG.',
            'foto_ktp.max'          => 'Foto KTP maksimal 2MB.',
            'pesan.required'        => 'Pesan wajib diisi.',
            'pesan.min'             => 'Pesan minimal 10 karakter.',
            'pesan.max'             => 'Pesan maksimal 1000 karakter.',
        ]);

        // ================== NORMALISASI DATA ==================
        $nik = preg_replace('/[^0-9]/', '', $request->nik);
        $nama = strtoupper(trim($request->nama_lengkap));
        $pesan = trim($request->pesan);

        // Extra validasi: pastikan NIK benar-benar 16 digit
        if (strlen($nik) !== 16) {
            return back()->withInput()->withErrors([
                'nik' => 'NIK harus 16 digit angka.'
            ]);
        }

        // Anti spam sederhana: kalau pesannya huruf sama terus
        $pesanNoSpace = preg_replace('/\s+/', '', $pesan);
        if (preg_match('/^(.)\1{12,}$/', $pesanNoSpace)) {
            return back()->withInput()->withErrors([
                'pesan' => 'Pesan terdeteksi tidak valid (spam).'
            ]);
        }

        // ================== LIMIT 1x PER HARI PER NIK ==================
        $today = Carbon::today();

        $sudahKirimHariIni = Kritik::where('nik', $nik)
            ->whereDate('created_at', $today)
            ->exists();

        if ($sudahKirimHariIni) {
            return back()->withInput()->withErrors([
                'nik' => 'NIK ini sudah mengirim kritik/saran hari ini. Silakan coba besok.'
            ]);
        }

        // ================== SIMPAN DATA ==================
        DB::transaction(function () use ($request, $nik, $nama, $pesan) {

            $pathKtp = $request->file('foto_ktp')->store('kritik_saran/ktp', 'public');

            Kritik::create([
                'nama_lengkap' => $nama,
                'jenis'        => $request->jenis,
                'nik'          => $nik,
                'foto_ktp'     => $pathKtp,
                'pesan'        => $pesan,
            ]);
        });

        return redirect('/kritik-saran')
            ->with('success', 'Terima kasih! Kritik/Saran berhasil dikirim.');
    }

    public function hapus($id)
    {
        $kritik = Kritik::find($id);

        if (!$kritik) {
            return response()->json([
                'success' => false,
                'message' => 'Data kritik & saran tidak ditemukan.'
            ]);
        }

        try {
            // Hapus file KTP jika ada
            if ($kritik->foto_ktp) {
                $path = public_path('storage/' . $kritik->foto_ktp);
                if (file_exists($path)) unlink($path);
            }

            $kritik->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data kritik & saran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
