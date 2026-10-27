<?php

namespace App\Observers;

use App\Models\Wbp;
use App\Models\WbpPembinaan;
use Illuminate\Support\Facades\DB;
use App\Models\Kamar;

class WbpObserver
{
    /**
     * Handle the Wbp "created" event.
     */
    public function created(Wbp $wbp): void
    {
        if (!$this->isSelMaksimum($wbp->kamar_id)) {
            return;
        }

        DB::transaction(function () use ($wbp) {
            $sekarang = now();

            DB::table('wbp_isolasi_histories')->insert([
                'wbp_id' => $wbp->id,
                'kamar_id' => $wbp->kamar_id,
                'tgl_masuk_isolasi' => $sekarang,
                'tgl_keluar_isolasi' => null,
                'diverifikasi_pada' => null,
                'diverifikasi_oleh' => null,
                'created_at' => $sekarang,
                'updated_at' => $sekarang,
            ]);

            if ($wbp->status_kamar !== 'Tertutup') {
                $wbp->status_kamar = 'Tertutup';
                $wbp->save();
            }
        });
    }

    /**
     * Handle the Wbp "updated" event.
     */

    public function updated(Wbp $wbp): void
    {

        // Catat perubahan masuk/keluar Sel Maksimum.
        if ($wbp->wasChanged('kamar_id')) {
            $kamarLamaId = $wbp->getOriginal('kamar_id');
            $kamarBaruId = $wbp->kamar_id;

            $sebelumnyaSelMaksimum = $this->isSelMaksimum($kamarLamaId);
            $sekarangSelMaksimum = $this->isSelMaksimum($kamarBaruId);

            // Keluar dari Sel Maksimum: tutup periode aktif.
            if ($sebelumnyaSelMaksimum && !$sekarangSelMaksimum) {
                $sekarang = now();

                DB::table('wbp_isolasi_histories')
                    ->where('wbp_id', $wbp->id)
                    ->whereNull('tgl_keluar_isolasi')
                    ->update([
                        'tgl_keluar_isolasi' => $sekarang,
                        'updated_at' => $sekarang,
                    ]);
            }

            // Masuk dari luar Sel Maksimum: mulai periode baru.
            if (!$sebelumnyaSelMaksimum && $sekarangSelMaksimum) {
                DB::transaction(function () use ($wbp, $kamarBaruId) {
                    $sekarang = now();

                    // Tutup riwayat aktif yang mungkin tersisa akibat data lama.
                    DB::table('wbp_isolasi_histories')
                        ->where('wbp_id', $wbp->id)
                        ->whereNull('tgl_keluar_isolasi')
                        ->update([
                            'tgl_keluar_isolasi' => $sekarang,
                            'updated_at' => $sekarang,
                        ]);

                    DB::table('wbp_isolasi_histories')->insert([
                        'wbp_id' => $wbp->id,
                        'kamar_id' => $kamarBaruId,
                        'tgl_masuk_isolasi' => $sekarang,
                        'tgl_keluar_isolasi' => null,
                        'diverifikasi_pada' => null,
                        'diverifikasi_oleh' => null,
                        'created_at' => $sekarang,
                        'updated_at' => $sekarang,
                    ]);

                    if ($wbp->status_kamar !== 'Tertutup') {
                        $wbp->status_kamar = 'Tertutup';
                        $wbp->save();
                    }
                });
            }
        }

        // Jalankan hanya jika status_wbp berubah.
        if (!$wbp->wasChanged('status_wbp')) {
            return;
        }

        $statusWbp = strtoupper(trim((string) $wbp->status_wbp));

        $statusAkhir = [
            'PULANG',
            'PINDAH UPT',
            'MENINGGAL',
        ];

        // Hanya proses status akhir yang ditentukan.
        if (!in_array($statusWbp, $statusAkhir, true)) {
            return;
        }

        DB::transaction(function () use ($wbp) {
            WbpPembinaan::query()
                ->where('wbp_id', $wbp->id)
                ->where('status_keikutsertaan', 'Aktif')
                ->update([
                    'status_keikutsertaan' => 'Selesai',
                    'tanggal_selesai' => now()->toDateString(),
                    'updated_at' => now(),
                ]);
        });
    }


    /**
     * Handle the Wbp "deleted" event.
     */
    public function deleted(Wbp $wbp): void
    {
        //
    }

    /**
     * Handle the Wbp "restored" event.
     */
    public function restored(Wbp $wbp): void
    {
        //
    }

    /**
     * Handle the Wbp "force deleted" event.
     */
    public function forceDeleted(Wbp $wbp): void
    {
        //
    }

    private function isSelMaksimum($kamarId): bool
    {
        if (!$kamarId) {
            return false;
        }

        return Kamar::query()
            ->where('id', $kamarId)
            ->where('kode_blok', 'B')
            ->whereIn('lokasi_sel', [
                'KAMAR 7',
                'KAMAR 8',
                'KAMAR 9',
                'KAMAR 10',
                'KAMAR 11',
                'KAMAR 12',
            ])
            ->exists();
    }
}
