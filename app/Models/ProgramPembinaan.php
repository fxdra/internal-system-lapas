<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramPembinaan extends Model
{
    protected $table = 'program_pembinaan';

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function keikutsertaan(): HasMany
    {
        return $this->hasMany(WbpPembinaan::class, 'program_pembinaan_id');
    }
}
