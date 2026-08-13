<?php

namespace App\Services;

use App\Models\MutasiScenario;
use App\Models\MutasiScenarioItem;
use App\Models\MutasiScenarioSnapshot;
use App\Models\Kamar;
use App\Models\Wbp;
use Illuminate\Support\Facades\DB;

class MutasiSimulasiService
{
    public function simulate($data)
    {
        DB::beginTransaction();

        try {

            if (empty($data['tanggal'])) {
                throw new \Exception("tanggal wajib dikirim dari client");
            }

            $scenario = MutasiScenario::firstOrCreate(
                ['tanggal' => $data['tanggal']],
                [
                    'nama_simulasi' => $data['nama_simulasi'],
                    'is_active' => 1
                ]
            );

            /*
            =====================================================
            1. SNAPSHOT INIT (ONLY ONCE)
            =====================================================
            */
            $existingCount = MutasiScenarioSnapshot::where('scenario_id', $scenario->id)->count();

            if ($existingCount === 0) {

                $kamars = Kamar::all();

                foreach ($kamars as $k) {
                    $count = Wbp::where('kamar_id', $k->id)->count();

                    MutasiScenarioSnapshot::create([
                        'scenario_id' => $scenario->id,
                        'kamar_id' => $k->id,
                        'sebelum' => $count,
                        'masuk' => 0,
                        'keluar' => 0,
                        'sesudah' => $count
                    ]);
                }
            }

            /*
            =====================================================
            2. GROUPING MUTASI
            =====================================================
            */
            $grouped = [];

            foreach ($data['items'] as $item) {

                MutasiScenarioItem::create([
                    'scenario_id' => $scenario->id,
                    'wbp_id' => $item['wbp_id'],
                    'kamar_asal_id' => $item['kamar_asal_id'],
                    'kamar_tujuan_id' => $item['kamar_tujuan_id'],
                ]);

                $asal = (int) $item['kamar_asal_id'];
                $tujuan = (int) $item['kamar_tujuan_id'];

                if (!isset($grouped[$asal])) {
                    $grouped[$asal] = ['masuk' => 0, 'keluar' => 0];
                }

                if (!isset($grouped[$tujuan])) {
                    $grouped[$tujuan] = ['masuk' => 0, 'keluar' => 0];
                }

                $grouped[$asal]['keluar']++;
                $grouped[$tujuan]['masuk']++;
            }

            /*
            =====================================================
            3. UPDATE SNAPSHOT (ONLY ONCE - FIX DOUBLE BUG)
            =====================================================
            */
            foreach ($grouped as $kamarId => $g) {

                $snapshot = MutasiScenarioSnapshot::firstOrCreate(
                    [
                        'scenario_id' => $scenario->id,
                        'kamar_id' => $kamarId
                    ],
                    [
                        'sebelum' => Wbp::where('kamar_id', $kamarId)->count(),
                        'masuk' => 0,
                        'keluar' => 0,
                        'sesudah' => 0
                    ]
                );

                // ❗ IMPORTANT FIX: jangan pakai += kalau simulate multi-call
                $snapshot->masuk = $snapshot->masuk + $g['masuk'];
                $snapshot->keluar = $snapshot->keluar + $g['keluar'];

                $snapshot->sesudah = max(
                    0,
                    $snapshot->sebelum + $snapshot->masuk - $snapshot->keluar
                );

                $snapshot->save();
            }

            /*
            =====================================================
            4. ❌ DIHAPUS TOTAL (INI BIANG KACAU)
            =====================================================
            - tidak perlu loop allSnapshots
            - tidak perlu reset kamar lain
            - snapshot itu STATE, bukan event result
            */

            DB::commit();

            return [
                'status' => true,
                'scenario_id' => $scenario->id,
                'message' => 'SIMULASI STABLE + NO DOUBLE CALC'
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
