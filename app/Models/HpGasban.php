<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HpGasban extends Model
{
   protected $guarded = ['id'];
   protected $casts = [

    'foto_handphone' => 'array',
    'jenis_hp'       => 'array',
    'warna_hp'       => 'array',

    ];
}
