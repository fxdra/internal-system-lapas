<?php

namespace App\Models;

use App\Models\KategoriBuku;
use App\Models\PengunjungPerpustakaan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Buku extends Model
{
    protected $table = "buku";

    protected $guarded = [
        'id',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(PengunjungPerpustakaan::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->judul);
        });
    }
}
