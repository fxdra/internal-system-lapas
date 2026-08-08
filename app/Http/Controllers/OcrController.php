<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Log, Storage};
use App\Helpers\OcrHelper;

class OcrController extends Controller
{
   public function ocrKtp(Request $request)
{
    try {
        $request->validate([
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096'
        ]);

        $path = $request->file('foto_ktp')->store('tmp-ktp', 'public');
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            throw new \Exception('File tidak ditemukan');
        }

        // 🔥 ENHANCE IMAGE DULU
        $enhancedPath = $this->enhanceImageForOcr($fullPath);

        // 🔥 OCR pakai image hasil enhancement
        $nik  = OcrHelper::extractNik($enhancedPath);
        $nama = OcrHelper::extractNama($enhancedPath);

        Log::info('OCR KTP', [
            'nik'  => $nik,
            'nama' => $nama,
            'ip'   => $request->ip(),
        ]);

        // cleanup file
        Storage::disk('public')->delete($path);

        if (file_exists($enhancedPath)) {
            unlink($enhancedPath);
        }

        return response()->json([
            'success' => (bool) $nik,
            'nik' => $nik,
            'nama' => $nama,
            'message' => $nik
                ? 'OCR berhasil (enhanced image)'
                : 'OCR gagal, coba foto lebih jelas'
        ]);

    } catch (\Throwable $e) {
        Log::error('OCR ERROR', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal membaca KTP'
        ], 500);
    }
}
    
    private function enhanceImageForOcr(string $inputPath): string
{
    $outputPath = str_replace('.jpg', '_enhanced.jpg', $inputPath);
    $outputPath = str_replace('.png', '_enhanced.png', $outputPath);

    $cmd = "convert " . escapeshellarg($inputPath) . "
        -resize 300%
        -colorspace Gray
        -contrast-stretch 0
        -sharpen 0x1.2
        -density 300
        " . escapeshellarg($outputPath);

    exec($cmd);

    return $outputPath;
}
    
}
