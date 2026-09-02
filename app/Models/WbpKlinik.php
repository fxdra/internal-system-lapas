<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WbpKlinik extends Model
{
    protected $table = 'wbp_klinik';

    protected $primaryKey = 'wbp_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'wbp_id',
        'no_rekam_medis',
    ];

    public function wbp(): BelongsTo
    {
        return $this->belongsTo(Wbp::class, 'wbp_id', 'id');
    }
}
