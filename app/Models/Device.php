<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $guarded = ['id'];

    public function logs()
    {
        return $this->hasMany(DeviceLog::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }
}
