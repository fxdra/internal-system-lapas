<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    protected $service;

    // 🔥 MULTI SPREADSHEET ID
    protected $spreadsheetIds = [
        'mutasi' => '1wwGqHyH5TqrQFTQjM4zZ8ibsBq9pLaexw_w8THDahPc',
        'wbp'  => '1c-2SIX1Vx2fmwu6CfaXsMS-CmWaJtV8HErD1IGZaIaA',
    ];

    public function __construct()
    {
        $client = new Client();

        $client->setApplicationName('Laravel Google Sheet');
        $client->setScopes([Sheets::SPREADSHEETS]);

        $client->setAuthConfig(
            storage_path('app/google-sheet.json')
        );

        $this->service = new Sheets($client);
    }

    // ================= APPEND =================
    public function append($data, $type = 'mutasi', $sheetName = 'Sheet1')
    {
        try {

            $spreadsheetId = $this->spreadsheetIds[$type] ?? null;

            if (!$spreadsheetId) {
                throw new \Exception("Spreadsheet ID untuk {$type} tidak ditemukan");
            }

            Log::info('GoogleSheet TRY APPEND', [
                'type' => $type,
                'spreadsheetId' => $spreadsheetId,
                'data' => $data
            ]);

            // 🔥 CLEAN DATA
            $cleanData = array_map(function ($row) {
                return array_map(function ($col) {
                    return $col ?? '';
                }, array_values($row));
            }, $data);

            $body = new ValueRange();
            $body->setValues($cleanData);

            $response = $this->service->spreadsheets_values->append(
                $spreadsheetId,
                $sheetName . '!A:P',
                $body,
                ['valueInputOption' => 'RAW']
            );

            Log::info('GoogleSheet SUCCESS', [
                'type' => $type,
                'updatedRange' => $response->getUpdates()->getUpdatedRange() ?? null
            ]);

            return $response;

        } catch (\Exception $e) {

            Log::error('GoogleSheet ERROR', [
                'message' => $e->getMessage(),
                'type' => $type ?? null
            ]);

            return null;
        }
    }
    
    
     // ================= APPEND OR UPDATE =================
    public function appendOrUpdate($data, $type = 'mutasi', $sheetName = 'Sheet1')
    {
        try {
    
            $spreadsheetId = $this->spreadsheetIds[$type] ?? null;
    
            if (!$spreadsheetId) {
                throw new \Exception("Spreadsheet ID untuk {$type} tidak ditemukan");
            }
    
            // 🔥 AMBIL SEMUA DATA SHEET
            $existing = $this->service->spreadsheets_values->get(
                $spreadsheetId,
                $sheetName . '!A:P'
            );
    
            $values = $existing->getValues();
    
            foreach ($data as $rowData) {
    
                // kolom B = wbp_id
                $wbpId = $rowData[1] ?? null;
    
                $foundRow = null;
    
                foreach ($values as $index => $row) {
    
                    if (isset($row[1]) && $row[1] == $wbpId) {
    
                        $foundRow = $index + 1;
    
                        // 🔥 created_at tetap ambil data lama
                       if (isset($row[14]) && !empty($row[14])) {
                                try {
                                    $rowData[14] = \Carbon\Carbon::parse($row[14])
                                        ->locale('id')
                                        ->translatedFormat('d F Y H:i:s');
                                } catch (\Exception $e) {
                                    $rowData[14] = now()
                                        ->locale('id')
                                        ->translatedFormat('d F Y H:i:s');
                                }
                            }
                        // 🔥 updated_at selalu terbaru
                        $rowData[15] = now()
                            ->locale('id')
                            ->translatedFormat('d F Y H:i:s');
    
                        break;
                    }
                }
    
                // 🔥 CLEAN DATA
                $cleanData = [array_map(function ($col) {
                    return $col ?? '';
                }, array_values($rowData))];
    
                $body = new ValueRange();
                $body->setValues($cleanData);
    
                // ======================================
                // UPDATE
                // ======================================
                if ($foundRow) {
    
                    $range = $sheetName . '!A' . $foundRow . ':P' . $foundRow;
    
                    $this->service->spreadsheets_values->update(
                        $spreadsheetId,
                        $range,
                        $body,
                        ['valueInputOption' => 'RAW']
                    );
    
                    Log::info('GoogleSheet UPDATED', [
                        'row' => $foundRow,
                        'wbp_id' => $wbpId
                    ]);
                }
    
                // ======================================
                // APPEND BARU
                // ======================================
                else {
    
                    $this->service->spreadsheets_values->append(
                        $spreadsheetId,
                        $sheetName . '!A:P',
                        $body,
                        ['valueInputOption' => 'RAW']
                    );
    
                    Log::info('GoogleSheet APPENDED', [
                        'wbp_id' => $wbpId
                    ]);
                }
            }
    
            return true;
    
        } catch (\Exception $e) {
    
            Log::error('GoogleSheet appendOrUpdate ERROR', [
                'message' => $e->getMessage(),
                'type' => $type ?? null
            ]);
    
            return false;
        }
    }

    // ================= GETTER SERVICE =================
    public function getService()
    {
        return $this->service;
    }

    // ================= GET ALL IDS =================
    public function getSpreadsheetIds()
    {
        return $this->spreadsheetIds;
    }

    // ================= GET SINGLE ID =================
    public function getSpreadsheetId($type = 'mutasi')
    {
        return $this->spreadsheetIds[$type] ?? null;
    }

    // ================= CHECK TYPE =================
    public function hasType($type)
    {
        return isset($this->spreadsheetIds[$type]);
    }
}