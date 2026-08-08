<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiScenario extends Model
{
    protected $guarded = [
        'id',
    ];
    
    public function items()
    {
        return $this->hasMany(MutasiScenarioItem::class, 'scenario_id');
    }

    public function snapshots()
    {
        return $this->hasMany(MutasiScenarioSnapshot::class, 'scenario_id');
    }
    
    
}
