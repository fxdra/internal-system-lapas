<?php

namespace App\Models;

use App\Models\Wbp;
use App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Titipan extends Model
{
    protected $table = 'titipans';
    protected $guarded = ['id'];

   
   public function wbp()
    {
        return $this->belongsTo(Wbp::class, 'wbp_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'titip_barang_id');
    }
}
