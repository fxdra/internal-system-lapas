<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiScenarioSnapshot extends Model
{
    
    protected $guarded = [
        'id',
    ];
    
    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function scenario()
    {
        return $this->belongsTo(MutasiScenario::class);
    }
}
