<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonWbp extends Model
{
    
    protected $guarded = [
        'id',
    ];
    
    public function wbp()
    {
        return $this->belongsTo(Wbp::class);
    }

    public function logs()
    {
        return $this->hasMany(BonWbpLog::class);
    }

    public function trackings()
    {
        return $this->hasMany(BonWbpTracking::class);
    }
    
    public function kamarAsal()
    {
        return $this->belongsTo(Kamar::class, 'kamar_asal_id');
    }
    
    
    
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'dibuat_oleh');
    }
    
    public function bon()
    {
        return $this->belongsTo(BonWbp::class);
    }
    
   

}
