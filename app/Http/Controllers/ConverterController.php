<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConverterController extends Controller
{
    public function convert(Request $request)
    {
        try {

            // =========================
            // VALIDASI
            // =========================
            if (!$request->hasFile('pdf')) {
                return response()->json([
                    'status' => false,
                    'msg' => 'File PDF tidak ditemukan'
                ]);
            }

            // =========================
            // PATH
            // =========================
            $pdfDir   = storage_path('app/pdf');
            $imgDir   = storage_path('app/images');
            $excelDir = storage_path('app/excel');

            foreach ([$pdfDir, $imgDir, $excelDir] as $dir) {
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                    Log::info("Folder dibuat: " . $dir);
                }
            }

            // =========================
            // UPLOAD PDF
            // =========================
            $file = $request->file('pdf');
            $fileName = time() . '_' . uniqid() . '.pdf';
            $pdfPath = $pdfDir . '/' . $fileName;

            $file->move($pdfDir, $fileName);
            Log::info("PDF disimpan: " . $pdfPath);

            // =========================
            // PDF → IMAGE
            // =========================
            $imgBase = $imgDir . '/page_' . time();

            $cmdConvert = "/usr/bin/pdftoppm -jpeg '$pdfPath' '$imgBase'";
            exec($cmdConvert . " 2>&1", $outConvert, $retConvert);

            Log::info("CMD pdftoppm: " . $cmdConvert);
            Log::info("RETURN: " . $retConvert);

            $images = glob($imgBase . '-*.jpg');

            if (empty($images)) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Gagal convert PDF ke image'
                ]);
            }

            // =========================
            // EXCEL PATH (DIHAPUS DULU BIAR BERSIH)
            // =========================
            $excelPath = $excelDir . '/hasil_' . time() . '.xlsx';
            if (file_exists($excelPath)) {
                unlink($excelPath);
            }

            $results = [];

            foreach ($images as $img) {

                Log::info("PROCESS IMAGE: " . $img);

                $pythonScript = base_path('phyton/ocr_processor.py');

                if (!file_exists($pythonScript)) {
                    throw new \Exception("Python script tidak ditemukan: " . $pythonScript);
                }

                // =========================
                // EXEC PYTHON (PENTING: RESET OUTPUT)
                // =========================
                $outPython = [];
                $retPython = 0;

                $cmd = "python3 '$pythonScript' '$img' '$excelPath'";
                exec($cmd . " 2>&1", $outPython, $retPython);

                Log::info("CMD PYTHON: " . $cmd);
                Log::info("RETURN PYTHON: " . $retPython);
                Log::info("OUTPUT PYTHON: " . implode("\n", $outPython));

                // =========================
                // PARSE OUTPUT
                // =========================
                foreach ($outPython as $line) {

                    if (!str_contains($line, '|')) continue;

                    $parts = explode('|', $line);

                    $results[] = [
                        'nama'  => trim($parts[0] ?? '-'),
                        'reg'   => trim($parts[1] ?? '-'),
                        'image' => $img
                    ];
                }
            }

            // =========================
            // RESPONSE
            // =========================
            return response()->json([
                'status' => true,
                'data'   => $results,
                'excel'  => $excelPath
            ]);

        } catch (\Throwable $e) {

            Log::error("ERROR CONVERTER", [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return response()->json([
                'status' => false,
                'msg' => 'Terjadi error saat convert'
            ]);
        }
    }
}