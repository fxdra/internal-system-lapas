<?php

namespace App\Models;

use App\Models\Wbp;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamars';
    protected $guarded = [
        'id',
    ];

    public function wbps()
    {
        return $this->hasMany(Wbp::class, 'kamar_id');
    }
    
    public function scenarioItems()
    {
        return $this->hasMany(MutasiScenarioSnapshot::class, 'kamar_id');
    }
    
    /* Penghuni kamar yang masih aktif.
     * Digunakan untuk operasional harian:
     * - Scan barcode kamar
     * - Data kamar
     * - Dashboard
     * - Detail penghuni per kamar
     */
    public function wbpsAktif()
    {
        return $this->hasMany(Wbp::class, 'kamar_id')
            ->where('status_wbp', 'AKTIF')
            ->orderBy('nama');
    }
}
