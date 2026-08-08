<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaziaKegiatanKamar extends Model
{
    protected $table = 'razia_kegiatan_kamar';

    protected $guarded = [];

    public $timestamps = false;

    public function kamar()
    {
        return $this->belongsTo(
            RaziaKamar::class,
            'kamar_id'
        );
    }
}
