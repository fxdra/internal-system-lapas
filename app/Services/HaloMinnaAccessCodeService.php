<?php

namespace App\Services;

use App\Models\SimproinWbp;
use App\Models\Wbp;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class HaloMinnaAccessCodeService
{
    public function ensureForWbp(Wbp $wbp): ?SimproinWbp
    {
        // Hanya WBP AKTIF yang mendapatkan kode akses.
        if ($wbp->status_wbp !== 'AKTIF') {
            return null;
        }

        return DB::transaction(function () use ($wbp) {
            $registrasi = SimproinWbp::firstOrNew([
                'wbp_id' => $wbp->id,
            ]);

            if (!$registrasi->exists) {
                $registrasi->register_f = 'Tidak';
                $registrasi->jenis_usulan = 'Belum Usul';
                $registrasi->status_bebas = 'Belum Bebas';
                $registrasi->status_aktif = 'Aktif';
            }

            // Jangan mengganti kode yang sudah ada.
            if (blank($registrasi->kode_akses)) {
                $registrasi->kode_akses = $this->generateUniqueCode();
            }

            if ($registrasi->isDirty()) {
                $registrasi->save();
            }

            return $registrasi;
        });
    }

    private function generateUniqueCode(): string
    {
        // I, O, 0, dan 1 tidak digunakan agar mudah dibaca.
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        for ($percobaan = 0; $percobaan < 20; $percobaan++) {
            $kode = 'BNCY-';

            for ($i = 0; $i < 8; $i++) {
                $kode .= $karakter[random_int(0, strlen($karakter) - 1)];
            }

            $kodeSudahDipakai = SimproinWbp::where(
                'kode_akses',
                $kode
            )->exists();

            $kodePernahDicabut = DB::table('riwayat_kode_akses')
                ->where('kode_akses_hash', hash('sha256', $kode))
                ->exists();

            if (!$kodeSudahDipakai && !$kodePernahDicabut) {
                return $kode;
            }
        }

        throw new RuntimeException(
            'Gagal membuat kode akses unik setelah 20 percobaan.'
        );
    }
}
