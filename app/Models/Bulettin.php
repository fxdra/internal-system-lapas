<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulettin extends Model
{
    protected $table = 'bulettins';
    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
