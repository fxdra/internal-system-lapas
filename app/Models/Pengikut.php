<?php

namespace App\Models;

use App\Models\Pengunjung;
use Illuminate\Database\Eloquent\Model;

class Pengikut extends Model
{
    protected $table = 'pengikuts';
    protected $guarded = ['id'];

    public function Pengunjung()
    {
        return $this->belongsTo(Pengunjung::class);
    }
}
