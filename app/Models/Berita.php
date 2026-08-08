<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'beritas';
    protected $guarded = ['id'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];
}
