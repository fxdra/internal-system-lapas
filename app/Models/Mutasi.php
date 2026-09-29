<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasis';
    protected $guarded = ['id'];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    public function kamarAsal()
    {
        return $this->belongsTo(Kamar::class, 'kamar_asal_id');
    }

    public function kamarTujuan()
    {
        return $this->belongsTo(Kamar::class, 'kamar_tujuan_id');
    }
}
