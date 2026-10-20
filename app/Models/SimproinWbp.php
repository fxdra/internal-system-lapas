<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class SimproinWbp extends Model
{
    use HasApiTokens;

    protected $table = 'simproin_wbp';

    protected $fillable = [
        'wbp_id',
        'kode_akses',
        'register_f',
        'jenis_usulan',
        'tgl_pengambilan_berkas',
        'tgl_pengembalian_berkas',
        'tgl_sidang_tpp',
        'tgl_permintaan_litmas',
        'tgl_litmas_bapas',
        'tgl_hasil_litmas',
        'tgl_input_sdp',
        'tgl_verifikasi_ditjen',
        'tgl_persetujuan_menteri',
        'tgl_otorisasi_sk',
        'tgl_pelaksanaan',
        'tgl_pembebasan',
        'status_bebas',
        'nama_penjamin',
        'hubungan_penjamin',
        'no_hp_penjamin',
        'alamat_penjamin',
        'status_aktif',
        'alasan_batal',
        'tgl_batal',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pengambilan_berkas' => 'date',
            'tgl_pengembalian_berkas' => 'date',
            'tgl_sidang_tpp' => 'date',
            'tgl_permintaan_litmas' => 'date',
            'tgl_litmas_bapas' => 'date',
            'tgl_hasil_litmas' => 'date',
            'tgl_input_sdp' => 'date',
            'tgl_verifikasi_ditjen' => 'date',
            'tgl_persetujuan_menteri' => 'date',
            'tgl_otorisasi_sk' => 'date',
            'tgl_pelaksanaan' => 'date',
            'tgl_pembebasan' => 'date',
            'tgl_batal' => 'datetime',
        ];
    }

    public function wbp(): BelongsTo
    {
        return $this->belongsTo(Wbp::class, 'wbp_id');
    }
}
