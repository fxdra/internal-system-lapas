<?php

namespace App\Http\Controllers;

use App\Models\Wbp;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use App\Helpers\WbpHelper;
use App\Models\Kamar;
use App\Services\KamarService;
use Illuminate\Support\Facades\Log;

class ImportFileController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file_excel'  => 'required|mimes:xls,xlsx,csv|max:10240',
            'import_mode' => 'required|in:append,update'
        ]);

        $file = $request->file('file_excel');

        $spreadsheet = IOFactory::load(
            $file->getPathname()
        );

        $rows = $spreadsheet
            ->getActiveSheet()
            ->toArray();

        $mode = $request->input('import_mode', 'append');

        $start = 1;
        $maxData = 2000;

        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $notFound = 0;
        $errors = [];

        $notFoundRows = [];

        $excelNoRegs = [];

        try {

            DB::beginTransaction();

            for ($i = $start; $i < count($rows); $i++) {

                if (
                    ($inserted + $updated + $skipped + $notFound)
                    >= $maxData
                ) {
                    break;
                }

                try {

                    $noReg = trim((string) ($rows[$i][1] ?? ''));

                    if ($noReg !== null) {
                        $excelNoRegs[] = $noReg;
                    }

                    $result = $this->importRow(
                        $rows[$i],
                        $mode
                    );

                    switch ($result) {

                        case 'inserted':
                            $inserted++;
                            break;

                        case 'updated':
                            $updated++;
                            break;

                        case 'skipped':
                            $skipped++;
                            break;

                        case 'not_found':
                            $notFound++;

                            $notFoundRows[] = [
                                'row' => $i + 1,
                                'no_reg_instansi' => $noReg,
                                'nama' => trim((string) ($rows[$i][2] ?? '')),
                            ];

                            break;
                    }
                } catch (\Exception $e) {

                    $errors[] = [
                        'row'   => $i + 1,
                        'error' => $e->getMessage()
                    ];

                    // lanjut ke baris berikutnya
                    continue;
                }
            }

            DB::commit();

            $excelNoRegs = array_unique($excelNoRegs);

            $missing = collect();

            if ($mode === 'update') {

                $missing = Wbp::select(
                    'id',
                    'no_reg_instansi',
                    'nama',
                    'status_wbp'
                )
                    ->whereNotIn(
                        'no_reg_instansi',
                        $excelNoRegs
                    )
                    ->orderBy('nama')
                    ->get();
            }
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Import gagal.',
                'error'   => $e->getMessage()
            ], 500);
        }

        return response()->json([

            'status' => true,

            'message' => 'Import data WBP berhasil.',

            'mode' => strtoupper($mode),

            'inserted' => $inserted,
            'updated' => $updated,
            'skipped' => $skipped,
            'not_found' => $notFound,

            'total_processed' =>
            $inserted
                + $updated
                + $skipped
                + $notFound,

            'error_count' => count($errors),
            'errors' => $errors,

            'not_found_count' => count($notFoundRows),
            'not_found_rows' => $notFoundRows,

            'missing_count' => $missing->count(),

            'missing' => $missing->map(function ($item) {
                return [
                    'id' => $item->id,
                    'no_reg_instansi' => $item->no_reg_instansi,
                    'nama' => $item->nama,
                    'status_wbp' => $item->status_wbp,
                ];
            })->values(),

        ]);
    }

    private function importRow(array $row, string $mode): string
    {
        $val = function ($v) {
            if ($v === null) {
                return null;
            }

            $v = trim((string) $v);

            return $v === '' ? null : $v;
        };

        $parseDate = function ($value) {
            if ($value === null || trim((string) $value) === '') {
                return null;
            }

            try {
                if (is_numeric($value)) {
                    return \PhpOffice\PhpSpreadsheet\Shared\Date
                        ::excelToDateTimeObject($value)
                        ->format('Y-m-d');
                }

                $ts = strtotime($value);

                if ($ts === false) {
                    throw new \Exception();
                }

                return date('Y-m-d', $ts);
            } catch (\Exception $e) {
                throw new \Exception(
                    "Format tanggal tidak valid: {$value}"
                );
            }
        };

        // =========================================================
        // IDENTITAS
        // =========================================================

        $no_reg = $val($row[1] ?? null);

        if (!$no_reg) {
            throw new \Exception(
                "No Registrasi Instansi wajib diisi."
            );
        }

        $nama = $val($row[2] ?? null);

        if (!$nama) {
            throw new \Exception(
                "Nama WBP wajib diisi."
            );
        }

        $negara = $val($row[3] ?? null);
        $agama = $val($row[4] ?? null);
        $jenis_kejahatan = $val($row[5] ?? null);

        // =========================================================
        // DATA KLINIK
        // =========================================================

        $tempat_lahir = $val($row[6] ?? null);

        $tgl_lahir = $parseDate(
            $row[7] ?? null
        );

        $nik = $val($row[8] ?? null);

        // =========================================================
        // PERKARA
        // =========================================================

        $pasal = $val($row[9] ?? null);

        $putusan = $val($row[10] ?? null);

        $putusan_bulan = $val(
            $row[11] ?? null
        );

        if (
            $putusan_bulan !== null &&
            !ctype_digit((string) $putusan_bulan)
        ) {
            throw new \Exception(
                "Putusan (bulan) harus berupa angka. Nilai: {$putusan_bulan}."
            );
        }

        // =========================================================
        // DATA MASUK LAPAS
        // =========================================================

        $tgl_masuk_lapas = $parseDate(
            $row[12] ?? null
        );

        // =========================================================
        // SUBSIDER
        // =========================================================

        $subsider_bulan = $val(
            $row[13] ?? null
        );

        $subsider_tahun = $val(
            $row[14] ?? null
        );

        $subsider_hari = $val(
            $row[15] ?? null
        );

        // =========================================================
        // DENDA SUBSIDER
        // =========================================================

        $denda_subsider = $val(
            $row[16] ?? null
        );

        if ($denda_subsider !== null) {

            $denda_subsider = str_replace(
                ['Rp.', 'Rp', 'rp.', 'rp'],
                '',
                $denda_subsider
            );

            $denda_subsider = trim(
                $denda_subsider
            );

            $denda_subsider = str_replace(
                ',',
                '',
                $denda_subsider
            );

            if (!is_numeric($denda_subsider)) {
                throw new \Exception(
                    "Denda subsider harus berupa nominal angka. Nilai: {$row[16]}"
                );
            }

            $denda_subsider = (int) $denda_subsider;
        }

        // =========================================================
        // EKSPIRASI
        // =========================================================

        $ekspirasi = $parseDate(
            $row[17] ?? null
        );

        // =========================================================
        // MASA PIDANA
        // =========================================================

        $masa_1_3 = $parseDate(
            $row[18] ?? null
        );

        $masa_1_2 = $parseDate(
            $row[19] ?? null
        );

        $masa_2_3 = $parseDate(
            $row[20] ?? null
        );

        // =========================================================
        // REMISI
        // =========================================================

        $total_bulan_remisi = $val(
            $row[21] ?? null
        );

        $total_hari_remisi = $val(
            $row[22] ?? null
        );

        // =========================================================
        // DATA YANG DIIMPORT
        // Digunakan untuk APPEND dan UPDATE
        // =========================================================

        $masterData = [
            'nama' => $nama,
            'negara' => $negara,
            'agama' => $agama,
            'jenis_kejahatan' => $jenis_kejahatan,

            // Klinik
            'tempat_lahir' => $tempat_lahir,
            'tgl_lahir' => $tgl_lahir,
            'nik' => $nik,

            // Perkara
            'pasal' => $pasal,
            'putusan' => $putusan,
            'putusan_bulan' => $putusan_bulan,

            // Masuk Lapas
            'tgl_masuk_lapas' => $tgl_masuk_lapas,

            // Pidana tambahan
            'subsider_bulan' => $subsider_bulan,
            'subsider_tahun' => $subsider_tahun,
            'subsider_hari' => $subsider_hari,
            'denda_subsider' => $denda_subsider,

            // Ekspirasi
            'ekspirasi' => $ekspirasi,

            // Masa pidana
            'masa_1_3' => $masa_1_3,
            'masa_1_2' => $masa_1_2,
            'masa_2_3' => $masa_2_3,

            // Remisi
            'total_bulan_remisi' => $total_bulan_remisi,
            'total_hari_remisi' => $total_hari_remisi,
        ];

        // =========================================================
        // APPEND
        // =========================================================

        if ($mode === 'append') {

            if (
                Wbp::where(
                    'no_reg_instansi',
                    $no_reg
                )->exists()
            ) {
                return 'skipped';
            }

            Wbp::create(
                array_merge(
                    [
                        'no_reg_instansi' => $no_reg,
                    ],
                    $masterData
                )
            );

            return 'inserted';
        }

        // =========================================================
        // UPDATE
        // =========================================================

        if ($mode === 'update') {

            $existing = Wbp::where(
                'no_reg_instansi',
                $no_reg
            )->first();

            if (!$existing) {
                return 'not_found';
            }

            $existing->update(
                $masterData
            );

            return 'updated';
        }

        throw new \InvalidArgumentException(
            "Mode import tidak valid: {$mode}"
        );
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file_excel'  => 'required|mimes:xls,xlsx,csv|max:10240',
            'import_mode' => 'required|in:append,update'
        ]);

        $file = $request->file('file_excel');

        $spreadsheet = IOFactory::load(
            $file->getPathname()
        );

        $rows = $spreadsheet
            ->getActiveSheet()
            ->toArray();

        $mode = $request->input('import_mode');

        // Header ada di index 0, data mulai dari index 1
        $start = 1;

        $total    = 0;
        $insert   = 0;
        $update   = 0;
        $skip     = 0;
        $notFound = 0;
        $invalid  = 0;

        // Detail No. Registrasi yang tidak ditemukan
        $notFoundRows = [];

        for ($i = $start; $i < count($rows); $i++) {

            // Hitung seluruh baris data Excel
            $total++;

            try {

                $noReg = trim((string) ($rows[$i][1] ?? ''));

                if ($noReg === '') {
                    $invalid++;
                    continue;
                }

                $exists = Wbp::where(
                    'no_reg_instansi',
                    $noReg
                )->exists();

                if ($mode === 'append') {

                    if ($exists) {
                        $skip++;
                    } else {
                        $insert++;
                    }
                } elseif ($mode === 'update') {

                    if ($exists) {

                        $update++;
                    } else {

                        $notFound++;

                        // Simpan detail baris Excel
                        $notFoundRows[] = [
                            'row' => $i + 1,
                            'no_reg_instansi' => $noReg,
                            'nama' => trim(
                                (string) ($rows[$i][2] ?? '')
                            ),
                        ];
                    }
                }
            } catch (\Throwable $e) {

                $invalid++;
            }
        }

        return response()->json([

            'status'  => true,
            'mode'    => strtoupper($mode),

            'total'   => $total,

            'insert'  => $insert,
            'update'  => $update,
            'skip'    => $skip,
            'not_found' => $notFound,

            'invalid' => $invalid,

            // Detail data yang tidak ditemukan
            'not_found_rows' => $notFoundRows,

        ]);
    }

    private function resolveInitialStatus(array $row): string
    {
        $status = strtoupper(trim((string) ($row[11] ?? '')));

        $valid = [
            'AKTIF',
            'BON',
            'SAKIT',
            'PINDAH UPT',
            'PULANG',
            'MENINGGAL',
        ];

        return in_array($status, $valid, true)
            ? $status
            : 'AKTIF';
    }
}
