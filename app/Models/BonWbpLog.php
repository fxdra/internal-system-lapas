<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonWbpLog extends Model
{
    protected $guarded = ['id'];
     // ================= BON RELATION =================
    public function bonWbp()
    {
        return $this->belongsTo(BonWbp::class);
    }

    // ================= ADMIN RELATION =================
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
