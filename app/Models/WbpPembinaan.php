<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WbpPembinaan extends Model
{
    protected $table = 'wbp_pembinaan';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function wbp(): BelongsTo
    {
        return $this->belongsTo(Wbp::class, 'wbp_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramPembinaan::class, 'program_pembinaan_id');
    }
}
