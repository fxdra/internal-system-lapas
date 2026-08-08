<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Pengikut, Wbp};

class Pengunjung extends Model
{
    protected $table = 'pengunjungs';
    protected $guarded = ['id'];

    public function Pengikut()
    {
        return $this->hasMany(Pengikut::class, 'pengunjung_id');
    }

     public function wbp()
    {
       return $this->belongsTo(
        Wbp::class,
        'no_reg_instansi',   // foreign key di pengunjungs
        'no_reg_instansi'    // owner key di wbps
         );
    }
}
