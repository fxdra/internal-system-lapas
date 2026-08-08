<?php

namespace App\Http\Controllers;

use App\Models\Wbp;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
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

        $start = 2;
        $maxData = 2000;

        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        $excelNoRegs = [];

        try {

            DB::beginTransaction();

            for ($i = $start; $i < count($rows); $i++) {

                if (($inserted + $updated + $skipped) >= $maxData) {
                    break;
                }

                try {

                    $noReg = trim((string) ($rows[$i][1] ?? ''));

                    if ($noReg !== '') {
                        $excelNoRegs[] = $noReg;
                    }

                    $result = $this->importRow(
                        $rows[$i],
                        $i,
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
                    'nama'
                )
                    ->whereNotIn('no_reg_instansi', $excelNoRegs)
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

            'total_processed' => $inserted + $updated + $skipped,

            'error_count' => count($errors),
            'errors' => $errors,

            'missing_count' => $missing->count(),

            'missing' => $missing->map(function ($item) {

                return [
                    'id' => $item->id,
                    'no_reg_instansi' => $item->no_reg_instansi,
                    'nama' => $item->nama,
                ];
            })->values(),

        ]);
    }

    private function importRow(array $row, int $index, string $mode): string
    {
        $val = function ($v) {

            if ($v === null) {
                return null;
            }

            $v = trim((string) $v);

            return $v === ''
                ? null
                : $v;
        };

        // =========================
        // DATA DASAR
        // =========================

        $no_reg = $val($row[1] ?? null);

        if (!$no_reg) {
            throw new \Exception("No Registrasi Instansi wajib diisi.");
        }

        $nama = $val($row[2] ?? null);

        if (!$nama) {
            throw new \Exception("Nama WBP wajib diisi.");
        }

        $negara = $val($row[3] ?? null);
        $agama = $val($row[4] ?? null);

        $putusan = $val($row[5] ?? null);
        $putusan_bulan = $val($row[6] ?? null);
        if ($putusan_bulan !== null && !ctype_digit((string) $putusan_bulan)) {
            throw new \Exception("Putusan (bulan) harus berupa angka. Nilai: {$putusan_bulan}.");
        }
        $jenis_kejahatan = $val($row[7] ?? null);

        // =========================
        // EKSPIRASI
        // =========================

        $ekspirasi = null;

        if (!empty($row[8])) {

            try {

                if (is_numeric($row[8])) {

                    $ekspirasi =
                        \PhpOffice\PhpSpreadsheet\Shared\Date
                        ::excelToDateTimeObject($row[8])
                        ->format('Y-m-d');
                } else {

                    $ts = strtotime($row[8]);

                    if ($ts) {
                        $ekspirasi = date('Y-m-d', $ts);
                    }
                }
            } catch (\Exception $e) {
                $ekspirasi = null;
            }
        }

        // =========================
        // MASA PIDANA
        // =========================

        $masa_1_3 = $val($row[14] ?? null);
        $masa_1_2 = $val($row[15] ?? null);
        $masa_2_3 = $val($row[16] ?? null);

        // =========================
        // LOKASI
        // =========================

        // Ambil nilai dari file Excel SDP
        $lokasi_blok = strtoupper(trim($val($row[9] ?? null) ?? ''));
        $lokasi_sel  = strtoupper(trim($val($row[10] ?? null) ?? ''));

        $tidakAdaKamar = $lokasi_blok === '' || $lokasi_sel === '';

        if (!$tidakAdaKamar) {

            // Konversi format SDP -> format database
            $room = KamarService::convertSdpRoom(
                $lokasi_blok,
                $lokasi_sel
            );

            $lokasi_blok = $room['kode_blok'];
            $lokasi_sel  = $room['lokasi_sel'];
        }

        $kamar = null;

        if ($lokasi_blok !== '' && $lokasi_sel !== '') {

            try {

                $kamar = Kamar::where('kode_blok', $lokasi_blok)
                    ->where('lokasi_sel', $lokasi_sel)
                    ->sole();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

                throw new \Exception(
                    "Master kamar tidak ditemukan (Blok: {$lokasi_blok}, Sel: {$lokasi_sel})."
                );
            } catch (\Illuminate\Database\MultipleRecordsFoundException $e) {

                throw new \Exception(
                    "Master kamar ganda (Blok: {$lokasi_blok}, Sel: {$lokasi_sel}). Hubungi Administrator."
                );
            }
        }

        // =========================
        // STATUS WBP
        // =========================

        $status_wbp = $this->resolveInitialStatus($row);

        // =========================
        // REMISI
        // =========================
        $total_bulan_remisi = $val($row[17] ?? null);
        $total_hari_remisi  = $val($row[18] ?? null);

        // =========================
        // FOTO
        // =========================
        $foto = $val($row[23] ?? null);

        if ($foto) {

            $filename = basename($foto);

            $foto = 'storage/foto_wbp/' . $filename;
        }

        // =========================
        // MASTER DATA (SDP)
        // =========================
        $masterData = [

            'nama'                => $nama,

            'negara'              => $negara,
            'agama'               => $agama,

            'putusan'             => $putusan,
            'putusan_bulan'       => $putusan_bulan,
            'jenis_kejahatan'     => $jenis_kejahatan,

            'ekspirasi'           => $ekspirasi,

            'masa_1_3'            => $masa_1_3,
            'masa_1_2'            => $masa_1_2,
            'masa_2_3'            => $masa_2_3,

            'total_bulan_remisi'  => $total_bulan_remisi,
            'total_hari_remisi'   => $total_hari_remisi,

            'foto_wbp'            => $foto,
        ];

        $operasionalDefault = [

            // Status awal WBP
            'status_wbp' => $status_wbp,

            // Lokasi awal dari SDP
            'lokasi_blok' => $lokasi_blok ?: null,
            'lokasi_sel'  => $lokasi_sel ?: null,

            // Relasi ke tabel kamar
            'kamar_id' => $kamar?->id,

            // Status kamar mengikuti master kamar
            'status_kamar' => $kamar?->status_kamar ?? 'Terbuka',
        ];

        // =========================
        // APPEND
        // =========================

        if ($mode === 'append') {

            if (Wbp::where('no_reg_instansi', $no_reg)->exists()) {
                return 'skipped';
            }

            Wbp::create(array_merge(
                [
                    'no_reg_instansi' => $no_reg,
                ],
                $operasionalDefault,
                $masterData,
            ));

            return 'inserted';
        }
        // =========================
        // UPDATE
        // =========================

        if ($mode === 'update') {

            $existing = Wbp::where(
                'no_reg_instansi',
                $no_reg
            )->first();

            // Belum ada -> insert baru
            if (!$existing) {

                Wbp::create(array_merge(
                    [
                        'no_reg_instansi' => $no_reg,
                    ],
                    $operasionalDefault,
                    $masterData,
                ));

                return 'inserted';
            }

            /*
            |--------------------------------------------------------------------------
            | Update Data Master SDP
            |--------------------------------------------------------------------------
            | Yang diambil dari SDP:
            | - nama
            | - negara
            | - agama
            | - jenis_kejahatan
            | - putusan
            | - putusan_bulan
            | - ekspirasi
            | - masa pidana
            | - remisi
            | - lokasi_blok
            | - lokasi_sel
            |
            | Yang TIDAK diubah:
            | - kamar_id
            | - status_kamar
            | - status_wbp
            | - foto_wbp
            | - keperluan
            | - tanggal
            | - keterangan
            |--------------------------------------------------------------------------
            */

            $existing->update([

                'nama'               => $nama,

                'negara'             => $negara,
                'agama'              => $agama,

                'jenis_kejahatan'    => $jenis_kejahatan,

                'putusan'            => $putusan,
                'putusan_bulan'      => $putusan_bulan,

                'ekspirasi'          => $ekspirasi,

                'masa_1_3'           => $masa_1_3,
                'masa_1_2'           => $masa_1_2,
                'masa_2_3'           => $masa_2_3,

                'total_bulan_remisi' => $total_bulan_remisi,
                'total_hari_remisi'  => $total_hari_remisi,

                // Update penempatan sesuai SDP
                'lokasi_blok'        => $lokasi_blok ?: null,
                'lokasi_sel'         => $lokasi_sel ?: null,

            ]);

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

        // Mulai dari baris ke-3 (index 2)
        $start = 2;

        $total   = 0;
        $insert  = 0;
        $update  = 0;
        $invalid = 0;

        for ($i = $start; $i < count($rows); $i++) {

            // Hitung seluruh baris data Excel
            $total++;

            try {

                $noReg = trim($rows[$i][1] ?? '');

                if ($noReg === '') {
                    $invalid++;
                    continue;
                }

                if ($mode === 'append') {

                    $insert++;
                } elseif ($mode === 'update') {

                    $exists = Wbp::where(
                        'no_reg_instansi',
                        $noReg
                    )->exists();

                    if ($exists) {

                        $update++;
                    } else {

                        $insert++;
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
            'invalid' => $invalid
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
