<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    protected $guarded = ['id'];

    public function bukus()
    {
        return $this->hasMany(Buku::class);
    }
}
