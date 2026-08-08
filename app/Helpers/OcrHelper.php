<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrHelper
{
    private static function tesseract(string $imagePath): ?string
    {
        try {
            return (new TesseractOCR($imagePath))
                    ->executable('/usr/bin/tesseract')
                    ->lang('ind') // ⚠️ penting: jangan ind+eng dulu
                    ->psm(6)      // single line mode
                    ->oem(3)
                ->run();
        } catch (\Throwable $e) {
            Log::error('TESSERACT ERROR', [
                'msg' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Ambil NIK (16 digit)
     */
public static function extractNik(string $imagePath): ?string
{
    $text = self::tesseract($imagePath);
    if (!$text) return null;

    $text = strtoupper($text);

    // koreksi OCR umum
    $text = str_replace(
        ['O','I','L','S','B','Z','G'],
        ['0','1','1','5','8','2','6'],
        $text
    );

    preg_match_all('/\d+/', $text, $matches);

    if (empty($matches[0])) return null;

    $stream = implode('', $matches[0]);

    $best = null;
    $bestScore = 0;

    for ($i = 0; $i <= strlen($stream) - 16; $i++) {
        $cand = substr($stream, $i, 16);

        if (!preg_match('/^\d{16}$/', $cand)) continue;

        $score = self::scoreNik($cand);

        if ($score > $bestScore) {
            $bestScore = $score;
            $best = $cand;
        }
    }

    return $best;
}

private static function scoreNik(string $nik): int
{
    $score = 0;

    // aturan NIK Indonesia:
    // biasanya tidak random

    // 1. provinsi (2 digit pertama tidak boleh 00 / 99)
    $prov = substr($nik, 0, 2);
    if ($prov >= '11' && $prov <= '94') $score += 3;

    // 2. tanggal lahir valid-ish (digit 7-12)
    $tgl = substr($nik, 6, 2);
    if ($tgl >= 1 && $tgl <= 31) $score += 3;

    $bln = substr($nik, 8, 2);
    if ($bln >= 1 && $bln <= 12) $score += 3;

    // 3. tahun lahir masuk akal
    $thn = substr($nik, 10, 2);
    if ($thn >= 0 && $thn <= 99) $score += 2;

    return $score;
}

    /**
     * Ambil Nama Lengkap
     */
    public static function extractNama(string $imagePath): ?string
    {
        $text = self::tesseract($imagePath);

        if (!$text) return null;

        $textUpper = strtoupper($text);

        $lines = preg_split("/\r\n|\n|\r/", $textUpper);
        $lines = array_values(array_filter(array_map(function ($line) {
            $line = trim($line);

            // 🔥 FIX: jangan terlalu agresif hapus karakter
            $line = preg_replace('/[^A-Z\s]/', ' ', $line);
            $line = preg_replace('/\s+/', ' ', $line);

            return trim($line);
        }, $lines)));

        if (count($lines) === 0) return null;

        $stopWords = [
            'NIK',
            'TEMPAT',
            'TGL',
            'LAHIR',
            'JENIS',
            'KELAMIN',
            'GOL',
            'DARAH',
            'ALAMAT',
            'RT',
            'RW',
            'KEL',
            'DESA',
            'KECAMATAN',
            'AGAMA',
            'STATUS',
            'PEKERJAAN',
            'KEWARGANEGARAAN',
            'WNI',
            'BERLAKU',
            'HINGGA',
            'PROVINSI',
            'KABUPATEN',
            'KOTA'
        ];

        // =============================
        // MODE 1: "NAMA : XXXXX"
        // =============================
        foreach ($lines as $line) {
            if (preg_match('/\bNAMA\b\s*([A-Z\s]{3,})$/', $line, $m)) {
                $nama = self::cleanNama($m[1], $stopWords);
                if (self::isValidNama($nama)) return $nama;
            }
        }

        // =============================
        // MODE 2: BARIS SETELAH "NAMA"
        // =============================
        for ($i = 0; $i < count($lines); $i++) {
            if ($lines[$i] === 'NAMA') {
                $candidate = $lines[$i + 1] ?? null;
                if (!$candidate) continue;

                $candidate = self::cleanNama($candidate, $stopWords);

                if (self::isValidNama($candidate)) {
                    return $candidate;
                }
            }
        }

        // =============================
        // MODE 3: FALLBACK MULTILINE
        // =============================
        $joined = implode("\n", $lines);

        if (preg_match('/\bNAMA\b\s*\n\s*([A-Z\s]{3,})/m', $joined, $m)) {
            $nama = self::cleanNama($m[1], $stopWords);
            if (self::isValidNama($nama)) return $nama;
        }

        return null;
    }

    private static function cleanNama(string $nama, array $stopWords): string
    {
        $nama = preg_replace('/\s+/', ' ', trim($nama));

        $words = explode(' ', $nama);
        $result = [];

        foreach ($words as $w) {
            if (in_array($w, $stopWords, true)) break;

            $result[] = $w;

            if (count($result) >= 4) break;
        }

        $nama = implode(' ', $result);

        // 🔥 buang huruf tunggal nyangkut
        $nama = preg_replace('/\s+[A-Z]\b/', '', $nama);

        return trim($nama);
    }

    private static function isValidNama(string $nama): bool
    {
        if (!$nama) return false;
        if (str_word_count($nama) < 2) return false;
        if (strlen($nama) < 5) return false;
        if (!preg_match('/^[A-Z\s]+$/', $nama)) return false;
        if (strlen($nama) > 40) return false;

        return true;
    }
}
