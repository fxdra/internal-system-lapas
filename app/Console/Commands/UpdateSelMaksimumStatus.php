<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kamar;
use App\Models\Wbp;

class UpdateSelMaksimumStatus extends Command
{
    protected $signature = 'kamar:update-sel-maksimum';

    protected $description = 'Mengatur status Sel Maksimum B-7 sampai B-12 berdasarkan jadwal kunjungan';

    public function handle()
    {
        $hari = now()->dayOfWeekIso;

        // Senin = 1
        // Kamis = 4

        $status = in_array($hari, [1, 4])
            ? 'Terbuka'
            : 'Tertutup';

        $kamars = Kamar::where('kode_blok', 'B')
            ->whereIn('lokasi_sel', [
                'KAMAR 7',
                'KAMAR 8',
                'KAMAR 9',
                'KAMAR 10',
                'KAMAR 11',
                'KAMAR 12',
            ])
            ->get();

        foreach ($kamars as $kamar) {
            $kamar->update([
                'status_kamar' => $status,
            ]);

            Wbp::where('kamar_id', $kamar->id)
                ->update([
                    'status_kamar' => $status,
                ]);
        }

        $this->info(
            'Status Sel Maksimum berhasil diubah menjadi: ' . $status
        );

        return self::SUCCESS;
    }
}
