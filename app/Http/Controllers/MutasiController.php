<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mutasi;
use App\Models\Wbp;
use App\Models\Kamar;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MutasiController extends Controller
{
    public function index()
    {
        $data = Mutasi::whereNull('kamar_tujuan_nama')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function riwayatMutasi()
    {
        $data = Mutasi::with([
            'kamarAsal',
            'kamarTujuan'
        ])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            // CARI DATA MUTASI
            $mutasi = Mutasi::findOrFail($id);

            // CARI WBP
            $wbp = Wbp::findOrFail($mutasi->wbp_id);

            // CEK MUTASI TERAKHIR WBP
            $mutasiTerakhir = Mutasi::where('wbp_id', $mutasi->wbp_id)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->first();

            // JIKA BUKAN MUTASI TERAKHIR
            if (!$mutasiTerakhir || $mutasiTerakhir->id != $mutasi->id) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Riwayat Mutasi untuk WBP ini tidak dapat dihapus karena masih ada data sebelum periode ini.'
                    );
            }

            // SIMPAN DATA UNTUK GOOGLE SHEET
            $mutasiId = $mutasi->id;

            // KAMAR ASAL
            $kamarAsal = Kamar::find($mutasi->kamar_asal_id);

            if (!$kamarAsal) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Kamar asal tidak ditemukan. Mutasi tidak dapat dibatalkan.'
                    );
            }

            // KEMBALIKAN WBP KE KAMAR ASAL
            $wbp->update([
                'kamar_id'     => $kamarAsal->id,
                'lokasi_blok'  => $kamarAsal->lokasi_blok,
                'lokasi_sel'   => $kamarAsal->lokasi_sel,
                'status_kamar' => $kamarAsal->status_kamar,
            ]);

            // HAPUS RIWAYAT MUTASI
            $mutasi->delete();

            DB::commit();

            // GOOGLE SHEET
            try {

                $sheetService = app(GoogleSheetService::class);

                $service = $sheetService->getService();

                $spreadsheetId = $sheetService->getSpreadsheetId('mutasi');

                $range = 'Sheet1!A:P';

                $response = $service
                    ->spreadsheets_values
                    ->get($spreadsheetId, $range);

                $rows = $response->getValues();

                $rowIndex = null;

                // CARI BARIS BERDASARKAN MUTASI ID
                foreach ($rows as $i => $row) {

                    if (
                        isset($row[0]) &&
                        (string) $row[0] === (string) $mutasiId
                    ) {
                        $rowIndex = $i;
                        break;
                    }
                }

                // HAPUS BARIS GOOGLE SHEET
                if ($rowIndex !== null) {

                    $spreadsheet = $service
                        ->spreadsheets
                        ->get($spreadsheetId);

                    $sheetId = null;

                    foreach ($spreadsheet->getSheets() as $sheet) {

                        if (
                            $sheet->getProperties()->getTitle() === 'Sheet1'
                        ) {
                            $sheetId = $sheet
                                ->getProperties()
                                ->getSheetId();

                            break;
                        }
                    }

                    if ($sheetId !== null) {

                        $deleteRequest =
                            new \Google\Service\Sheets\DeleteDimensionRequest([
                                'range' => [
                                    'sheetId'    => $sheetId,
                                    'dimension'  => 'ROWS',
                                    'startIndex' => $rowIndex,
                                    'endIndex'   => $rowIndex + 1,
                                ]
                            ]);

                        $request =
                            new \Google\Service\Sheets\Request([
                                'deleteDimension' => $deleteRequest
                            ]);

                        $batchUpdateRequest =
                            new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                                'requests' => [$request]
                            ]);

                        $service
                            ->spreadsheets
                            ->batchUpdate(
                                $spreadsheetId,
                                $batchUpdateRequest
                            );
                    }
                }
            } catch (\Throwable $sheetError) {

                Log::error('GoogleSheet destroy mutasi ERROR', [
                    'mutasi_id' => $mutasiId,
                    'message'   => $sheetError->getMessage(),
                ]);
            }

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Mutasi berhasil dihapus dan WBP dikembalikan ke kamar asal.'
                );
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Destroy mutasi ERROR', [
                'mutasi_id' => $id,
                'message'   => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal menghapus mutasi: ' . $e->getMessage()
                );
        }
    }

    public function store(Request $req)
    {
        $wbp = Wbp::find($req->wbp_id);

        if (!$wbp) {
            return response()->json([
                'status' => false,
                'message' => 'WBP tidak ditemukan'
            ], 404);
        }

        $mutasi = Mutasi::where('wbp_id', $req->wbp_id)
            ->where('kamar_asal_id', $req->kamar_asal_id)
            ->first();

        $data = [

            // ================= WBP =================
            'wbp_id'            => $wbp->id,
            'nama'              => $req->nama ?? $wbp->nama,
            'negara'            => $req->negara ?? $wbp->negara,
            'agama'             => $req->agama ?? $wbp->agama,

            // ================= FIX TAMBAHAN =================
            'putusan'           => $req->putusan ?? $wbp->putusan,
            'ekspirasi'         => $req->ekspirasi ?? $wbp->ekspirasi,

            'jenis_kejahatan'   => $req->jenis_kejahatan ?? $wbp->jenis_kejahatan,
            'foto_wbp'          => $req->foto_wbp ?? $wbp->foto_wbp,

            // ================= KAMAR =================
            'kamar_asal_id'     => $req->kamar_asal_id,
            'kamar_asal_nama'   => $req->kamar_asal_nama
        ];

        // UPDATE
        if ($mutasi) {

            $mutasi->update($data);

            app(GoogleSheetService::class)->appendOrUpdate([
                [
                    $mutasi->id,
                    $data['wbp_id'],
                    $data['nama'],
                    $data['negara'],
                    $data['agama'],
                    $data['putusan'],
                    $data['ekspirasi'],
                    $data['jenis_kejahatan'],
                    $data['foto_wbp'],
                    $data['kamar_asal_id'],
                    $data['kamar_asal_nama'],
                    $req->kamar_tujuan_id ?? null,
                    $req->kamar_tujuan_nama ?? null,
                    $req->alasan ?? null,

                    // ================= created_at (FIX FORMAT) =================
                    $mutasi->created_at
                        ? $mutasi->created_at
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s')
                        : now()->locale('id')->translatedFormat('d F Y H:i:s'),

                    // ================= updated_at (ALWAYS NOW) =================
                    now()->locale('id')->translatedFormat('d F Y H:i:s'),
                ]
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Mutasi diperbarui',
                'data' => $mutasi
            ]);
        }

        // CREATE
        $mutasi = Mutasi::create($data);

        app(GoogleSheetService::class)->appendOrUpdate([
            [
                $mutasi->id,
                $data['wbp_id'],
                $data['nama'],
                $data['negara'],
                $data['agama'],
                $data['putusan'],
                $data['ekspirasi'],
                $data['jenis_kejahatan'],
                $data['foto_wbp'],
                $data['kamar_asal_id'],
                $data['kamar_asal_nama'],
                $req->kamar_tujuan_id ?? null,
                $req->kamar_tujuan_nama ?? null,
                $req->alasan ?? null,

                // ================= created_at =================
                now()->locale('id')->translatedFormat('d F Y H:i:s'),

                // ================= updated_at =================
                now()->locale('id')->translatedFormat('d F Y H:i:s'),
            ]
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Mutasi berhasil dibuat',
            'data' => $mutasi
        ]);
    }

    public function updateKamar(Request $req)
    {
        $req->validate([
            'wbp_id' => 'required',
            'barcode' => 'required'
        ]);

        // ================= FIND KAMAR =================
        $kamar = Kamar::where('barcode_id', $req->barcode)
            ->orWhere('kode_kamar', $req->barcode)
            ->first();

        if (!$kamar) {
            return response()->json([
                'status' => false,
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }

        // ================= FIND WBP =================
        $wbp = Wbp::find($req->wbp_id);

        if (!$wbp) {
            return response()->json([
                'status' => false,
                'message' => 'WBP tidak ditemukan'
            ], 404);
        }

        // ================= UPDATE WBP =================
        $wbp->update([
            'kamar_id' => $kamar->id,
            'lokasi_blok' => $kamar->lokasi_blok,
            'lokasi_sel' => $kamar->lokasi_sel
        ]);

        // ================= UPDATE MUTASI =================
        $mutasi = Mutasi::where('wbp_id', $wbp->id)
            ->latest()
            ->first();

        if ($mutasi) {

            $mutasi->update([
                'kamar_tujuan_id' => $kamar->id,
                'kamar_tujuan_nama' => $kamar->kode_kamar
            ]);

            try {

                $sheetService = app(\App\Services\GoogleSheetService::class);

                $service = $sheetService->getService();
                $spreadsheetId = $sheetService->getSpreadsheetId('mutasi');

                $range = 'Sheet1!A:P';

                $response = $service->spreadsheets_values->get($spreadsheetId, $range);
                $rows = $response->getValues();

                $rowIndex = null;

                // ================= FIND ROW BY wbp_id =================
                foreach ($rows as $i => $row) {
                    if (isset($row[1]) && (string)$row[1] === (string)$wbp->id) {
                        $rowIndex = $i + 1;
                        break;
                    }
                }

                if ($rowIndex) {

                    $body = new \Google\Service\Sheets\ValueRange([
                        'values' => [[
                            $rows[$rowIndex - 1][0] ?? '', // id mutasi
                            $wbp->id,
                            $wbp->nama,
                            $wbp->negara,
                            $wbp->agama,
                            $wbp->putusan,
                            $wbp->ekspirasi,
                            $wbp->jenis_kejahatan,
                            $wbp->foto_wbp,
                            $wbp->kamar_id,
                            $kamar->kode_kamar,
                            $kamar->id,
                            $kamar->kode_kamar,
                            '', // alasan

                            // ================= CREATED AT (tetap dari sheet lama) =================
                            $rows[$rowIndex - 1][14] ?? now()
                                ->locale('id')
                                ->translatedFormat('d F Y H:i:s'),

                            // ================= UPDATED AT (SELALU NOW) =================
                            now()->locale('id')
                                ->translatedFormat('d F Y H:i:s'),
                        ]]
                    ]);

                    $service->spreadsheets_values->update(
                        $spreadsheetId,
                        "Sheet1!A{$rowIndex}:P{$rowIndex}",
                        $body,
                        ['valueInputOption' => 'RAW']
                    );
                }
            } catch (\Throwable $e) {

                Log::error('GoogleSheet updateKamar ERROR', [
                    'message' => $e->getMessage(),
                    'wbp_id' => $wbp->id
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Mutasi berhasil diupdate',
            'data' => [
                'wbp' => $wbp,
                'mutasi' => $mutasi
            ]
        ]);
    }

    public function updateAlasan(Request $request)
    {
        try {

            $data = json_decode($request->getContent(), true);

            $wbpId = $data['wbp_id'] ?? null;
            $alasan = $data['alasan'] ?? '';

            if (!$wbpId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'wbp_id kosong'
                ], 400);
            }

            $exists = DB::table('mutasis')
                ->where('wbp_id', $wbpId)
                ->first();

            if ($exists) {

                DB::table('mutasis')
                    ->where('wbp_id', $wbpId)
                    ->update([
                        'alasan' => $alasan,
                        'updated_at' => now()
                    ]);
            } else {

                DB::table('mutasis')->insert([
                    'wbp_id' => $wbpId,
                    'alasan' => $alasan,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // ================= GOOGLE SHEET UPDATE =================
            try {

                $sheetService = app(\App\Services\GoogleSheetService::class);

                $service = $sheetService->getService();
                $spreadsheetId = $sheetService->getSpreadsheetId();

                $range = 'Sheet1!A:P';
                $response = $service->spreadsheets_values->get($spreadsheetId, $range);
                $rows = $response->getValues();

                $rowIndex = null;

                foreach ($rows as $i => $row) {
                    if (isset($row[1]) && (string)$row[1] === (string)$wbpId) {
                        $rowIndex = $i + 1;
                        break;
                    }
                }

                if ($rowIndex) {

                    $old = $rows[$rowIndex - 1];

                    $body = new \Google\Service\Sheets\ValueRange([
                        'values' => [[
                            $old[0] ?? '',
                            $old[1] ?? '',
                            $old[2] ?? '',
                            $old[3] ?? '',
                            $old[4] ?? '',
                            $old[5] ?? '',
                            $old[6] ?? '',
                            $old[7] ?? '',
                            $old[8] ?? '',
                            $old[9] ?? '',
                            $old[10] ?? '',
                            $old[11] ?? '',
                            $old[12] ?? '',
                            $alasan, // 🔥 UPDATE ALASAN DI SINI
                            $old[14] ?? '',
                            $old[15] ?? ''
                        ]]
                    ]);

                    $service->spreadsheets_values->update(
                        $spreadsheetId,
                        "Sheet1!A{$rowIndex}:P{$rowIndex}",
                        $body,
                        ['valueInputOption' => 'RAW']
                    );
                }
            } catch (\Throwable $e) {
                Log::error('GoogleSheet updateAlasan ERROR', [
                    'message' => $e->getMessage(),
                    'wbp_id' => $wbpId
                ]);
            }

            return response()->json([
                'status' => 'success',
                'action' => $exists ? 'update' : 'insert'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function dataMutasi(Request $request)
    {
        $request->validate([
            'filter'     => 'nullable|in:today,yesterday,3days,7days,1month,custom',
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date'   => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',
            'search'     => 'nullable|string|max:255',
        ]);

        $filter = $request->get('filter', 'today');

        $startDate = null;
        $endDate   = null;

        switch ($filter) {

            case 'today':
                $startDate = Carbon::today();
                $endDate   = Carbon::today();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate   = Carbon::yesterday();
                break;

            case '3days':
                $startDate = Carbon::today()->subDays(2);
                $endDate   = Carbon::today();
                break;

            case '7days':
                $startDate = Carbon::today()->subDays(6);
                $endDate   = Carbon::today();
                break;

            case '1month':
                $startDate = Carbon::today()->subMonth();
                $endDate   = Carbon::today();
                break;

            case 'custom':

                if (
                    !$request->filled('start_date') ||
                    !$request->filled('end_date')
                ) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Rentang tanggal wajib dipilih');
                }

                $startDate = Carbon::parse($request->start_date);
                $endDate   = Carbon::parse($request->end_date);

                break;
        }

        if ($startDate && $endDate) {

            $startDate = $startDate->copy()->startOfDay();
            $endDate   = $endDate->copy()->endOfDay();
        }

        $query = Mutasi::query();

        if ($request->filled('search')) {

            $query->where(
                'nama',
                'like',
                '%' . trim($request->search) . '%'
            );
        } elseif ($startDate && $endDate) {

            $query->whereBetween('created_at', [
                $startDate,
                $endDate
            ]);
        }

        Carbon::setLocale('id');

        $date = Carbon::now()->translatedFormat('d F Y');

        $kplp = Admin::where('role', 'ka. kplp')->first();

        $data = $query
            ->with([
                'kamarAsal',
                'kamarTujuan'
            ])
            ->orderByDesc('created_at')
            ->get();

        if (!$request->filled('search')) {

            // SORT NATURAL
            $data = $data->sort(function ($a, $b) {

                $blokA = $a->kamarAsal->kode_blok ?? '';
                $blokB = $b->kamarAsal->kode_blok ?? '';

                if ($blokA !== $blokB) {

                    return strcmp(
                        $blokA,
                        $blokB
                    );
                }

                preg_match(
                    '/(\d+)/',
                    $a->kamarAsal->lokasi_sel ?? '',
                    $ma
                );

                preg_match(
                    '/(\d+)/',
                    $b->kamarAsal->lokasi_sel ?? '',
                    $mb
                );

                $aNo = (int)($ma[1] ?? 0);
                $bNo = (int)($mb[1] ?? 0);

                return $aNo <=> $bNo;
            })->values();
        }

        $totalMutasi = $data->count();

        // GROUPING
        $grouped = $data->groupBy(function ($row) {

            $blok = $row->kamarAsal->kode_blok ?? null;

            if (!$blok) {
                return 'Tanpa Blok';
            }

            preg_match(
                '/(\d+)/',
                $row->kamarAsal->lokasi_sel ?? '',
                $match
            );

            $nomor = $match[1] ?? '-';

            return "BLOK {$blok} - KAMAR {$nomor}";
        });

        // DATA KAMAR
        $kamars = Kamar::get()
            ->sort(function ($a, $b) {

                if (($a->kode_blok ?? '') !== ($b->kode_blok ?? '')) {

                    return strcmp(
                        $a->kode_blok ?? '',
                        $b->kode_blok ?? ''
                    );
                }

                preg_match('/(\d+)/', $a->lokasi_sel ?? '', $ma);
                preg_match('/(\d+)/', $b->lokasi_sel ?? '', $mb);

                $aNo = (int)($ma[1] ?? 0);
                $bNo = (int)($mb[1] ?? 0);

                return $aNo <=> $bNo;
            })
            ->values();

        $wbps = Mutasi::with('kamarTujuan')
            ->select('nama')
            ->distinct()
            ->orderBy('nama')
            ->get()
            ->map(function ($item) {

                $last = Mutasi::with('kamarTujuan')
                    ->where('nama', $item->nama)
                    ->latest('created_at')
                    ->first();

                return (object) [
                    'nama'  => $item->nama,
                    'blok'  => $last?->kamarTujuan?->kode_blok ?? '-',
                    'kamar' => $last?->kamarTujuan?->lokasi_sel ?? '-',
                ];
            });

        return view(
            'admin-banceuy.riwayat-mutasi-wbp',
            compact(
                'grouped',
                'data',
                'filter',
                'startDate',
                'endDate',
                'date',
                'kplp',
                'kamars',
                'wbps',
                'totalMutasi'
            )
        );
    }

    public function wbpKamarUpdate(Request $request)
    {
        $request->validate([
            'wbp_ids'   => 'required|array|min:1',
            'wbp_ids.*' => 'exists:wbps,id',
            'kamar_id'  => 'required|exists:kamars,id'
        ]);

        $kamar = Kamar::findOrFail($request->kamar_id);

        foreach ($request->wbp_ids as $wbpId) {

            $wbp = Wbp::findOrFail($wbpId);

            // Simpan riwayat mutasi
            Mutasi::create([
                'wbp_id'             => $wbp->id,
                'nama'               => $wbp->nama,
                'negara'             => $wbp->negara,
                'agama'              => $wbp->agama,
                'putusan'            => $wbp->putusan,
                'ekspirasi'          => $wbp->ekspirasi,
                'jenis_kejahatan'    => $wbp->jenis_kejahatan,
                'foto_wbp'           => $wbp->foto_wbp,

                'kamar_asal_id'      => $wbp->kamar_id,
                'kamar_asal_nama'    => $wbp->lokasi_blok . ' - ' . $wbp->lokasi_sel,

                'kamar_tujuan_id'    => $kamar->id,
                'kamar_tujuan_nama'  => $kamar->lokasi_blok . ' - ' . $kamar->lokasi_sel,

                'alasan'             => 'Mutasi kamar'
            ]);

            // Update data WBP
            $wbp->update([
                'kamar_id'     => $kamar->id,
                'lokasi_blok'  => $kamar->lokasi_blok,
                'lokasi_sel'   => $kamar->lokasi_sel,
                'status_kamar' => $kamar->status_kamar,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => count($request->wbp_ids) . ' WBP berhasil dipindahkan.'
        ]);
    }

    public function updateKamarNew(Request $request, $id)
    {
        $request->validate([
            'kamar_tujuan_id' => 'required|exists:kamars,id',
            'alasan'          => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            // Data mutasi
            $mutasi = Mutasi::findOrFail($id);

            // Kamar tujuan baru
            $kamar = Kamar::findOrFail($request->kamar_tujuan_id);

            // Data WBP
            $wbp = Wbp::findOrFail($mutasi->wbp_id);

            // UPDATE TABEL MUTASI
            $mutasi->update([
                'kamar_tujuan_id'   => $kamar->id,
                'kamar_tujuan_nama' => $kamar->kode_kamar,
                'alasan'            => $request->alasan,
            ]);

            // ==========================
            // UPDATE TABEL WBP
            // ==========================
            $wbp->update([
                'kamar_id'     => $kamar->id,
                'lokasi_blok'  => $kamar->lokasi_blok,
                'lokasi_sel'   => $kamar->lokasi_sel,
                'status_kamar' => $kamar->status_kamar,
            ]);

            DB::commit();

            return redirect()->back()->with(
                'success',
                'Data mutasi berhasil diperbarui.'
            );
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
