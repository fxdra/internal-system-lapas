<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiScenarioItem extends Model
{
    
    protected $guarded = [
        'id',
    ];
    
    public function scenario()
    {
        return $this->belongsTo(MutasiScenario::class);
    }
    
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}
