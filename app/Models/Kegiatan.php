<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
     protected $table = 'kegiatans';
     protected $guarded = ['id'];
     
     protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
     ];

}
