<?php

namespace App\Models;

use App\Models\Kamar;
use App\Models\Pengunjung;
use App\Models\Titipan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Wbp extends Model
{
    protected $table = 'wbps';
    protected $guarded = ['id'];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = [
        'has_foto',
        'status_kelengkapan',
        'is_data_complete',
    ];

    public function pengunjungs()
    {
        return $this->hasMany(
            Pengunjung::class,
            'no_reg_instansi',   // foreign key di pengunjungs
            'no_reg_instansi'    // owner key di wbps
        );
    }

    public function titipBarang()
    {
        return $this->hasMany(\App\Models\Titipan::class, 'wbp_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function bonWbps()
    {
        return $this->hasMany(BonWbp::class);
    }

    /**
     * Mengembalikan status kelengkapan data WBP.
     */
    public function getStatusKelengkapanAttribute(): string
    {
        if (!empty($this->duplicate_no_reg)) {
            return 'DUPLIKAT_NO_REG';
        }

        if (!empty($this->duplicate_nama)) {
            return 'DUPLIKAT_NAMA';
        }

        if (!$this->has_foto) {
            return 'BELUM_ADA_FOTO';
        }

        if (blank($this->ekspirasi)) {
            return 'EKSPIRASI_KOSONG';
        }

        return 'LENGKAP';
    }

    /**
     * Mengecek apakah file foto WBP benar-benar ada di server.
     */
    public function getHasFotoAttribute(): bool
    {
        if (blank($this->foto_wbp)) {
            return false;
        }

        $path = $this->foto_wbp;

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return Storage::disk('public')->exists($path);
    }

    /**
     * Mengecek apakah data WBP sudah lengkap.
     */
    public function getIsDataCompleteAttribute(): bool
    {
        return $this->status_kelengkapan === 'LENGKAP';
    }
}
