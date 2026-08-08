<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaziaKegiatan extends Model
{
    protected $table = 'razia_kegiatan';

    protected $guarded = [];

    public function kamar()
    {
        return $this->hasMany(
            RaziaKegiatanKamar::class,
            'kegiatan_id'
        );
    }

    public function barang()
    {
        return $this->hasMany(
            RaziaKegiatanBarang::class,
            'kegiatan_id'
        );
    }

    public function foto()
    {
        return $this->hasMany(
            RaziaKegiatanFoto::class,
            'kegiatan_id'
        );
    }

    public function personel()
    {
        return $this->hasMany(
            RaziaKegiatanPersonel::class,
            'kegiatan_id'
        );
    }
}