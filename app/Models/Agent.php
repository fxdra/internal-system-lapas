<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'agents';

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    protected $casts = [
        'agent_balance' => 'decimal:2',
        'last_login_at' => 'datetime',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}