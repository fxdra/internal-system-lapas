<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produks';

    protected $guarded = [
        'id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI PRODUK
    |--------------------------------------------------------------------------
    */

    public function produks()
    {
        return $this->hasMany(Produk::class, 'category_id');
    }

    /*
    |--------------------------------------------------------------------------
    | PARENT CATEGORY
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(KategoriProduk::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | CHILD CATEGORY
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        return $this->hasMany(KategoriProduk::class, 'parent_id');
    }
}