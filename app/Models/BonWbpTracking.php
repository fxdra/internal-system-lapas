<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonWbpTracking extends Model
{
    protected $guarded = ['id'];
    public function bon()
    {
        return $this->belongsTo(BonWbp::class);
    }
}
