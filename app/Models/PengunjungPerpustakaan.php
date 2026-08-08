<?php

namespace App\Models;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengunjungPerpustakaan extends Model
{

    use HasFactory;
    protected $table = "pengunjung_perpustakaans";

    protected $guarded = [
        'id'
    ];


    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function kategori()
    {
        return $this->buku ? $this->buku->kategori() : null;
    }
}
