<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wbp;
use App\Models\Kamar;
use App\Models\Admin;

class WbpIsolasiHistory extends Model
{
    protected $table = 'wbp_isolasi_histories';

    protected $fillable = [
        'wbp_id',
        'kamar_id',
        'tgl_masuk_isolasi',
        'tgl_keluar_isolasi',
        'diverifikasi_pada',
        'diverifikasi_oleh',
        'diverifikasi_role',
    ];

    protected $casts = [
        'tgl_masuk_isolasi' => 'datetime',
        'tgl_keluar_isolasi' => 'datetime',
        'diverifikasi_pada' => 'datetime',
    ];

    public function wbp()
    {
        return $this->belongsTo(Wbp::class, 'wbp_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function petugasVerifikasi()
    {
        return $this->belongsTo(Admin::class, 'diverifikasi_oleh');
    }
}
