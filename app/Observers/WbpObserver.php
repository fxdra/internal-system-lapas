<?php

namespace App\Observers;

use App\Models\Wbp;
use App\Models\WbpPembinaan;
use Illuminate\Support\Facades\DB;

class WbpObserver
{
    /**
     * Handle the Wbp "created" event.
     */
    public function created(Wbp $wbp): void
    {
        //
    }

    /**
     * Handle the Wbp "updated" event.
     */
    public function updated(Wbp $wbp): void
    {
        // Jalankan hanya jika status_wbp berubah menjadi PULANG.
        if (
            $wbp->wasChanged('status_wbp') &&
            strtoupper(trim((string) $wbp->status_wbp)) === 'PULANG'
        ) {
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
}
