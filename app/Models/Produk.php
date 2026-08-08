<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';

    protected $guarded = [
        'id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI CATEGORY
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(
            KategoriProduk::class,
            'category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE PUBLISHED
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('is_active', true);
    }
}