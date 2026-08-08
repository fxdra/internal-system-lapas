<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaziaKamar extends Model
{
    protected $table = 'razia_kamar';

    protected $guarded = [];

    public $timestamps = false;

    public function blok()
    {
        return $this->belongsTo(
            RaziaBlok::class,
            'blok_id'
        );
    }
}
