<?php

namespace App\Models;

use App\Models\Titipan;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $guarded = ['id'];

   
    public function titipBarang()
    {
        return $this->belongsTo(Titipan::class, 'titip_barang_id');
    }
}
