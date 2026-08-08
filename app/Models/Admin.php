<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{

    use HasApiTokens, Notifiable; // <- ini wajib
    protected $table = 'admins';
    protected $guarded = ['id'];
    protected $hidden = [
        'password',
    ];

    public function bonWbps()
    {
        return $this->hasMany(BonWbp::class, 'dibuat_oleh');
    }

    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class, 'admin_id');
    }

    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            'superadmin' => 'Superadmin',
            'admin'      => 'Admin',
            'ka_kplp'    => 'Ka. KPLP',
            'kplp'       => 'KPLP',
            'registrasi' => 'Registrasi',
            'binadik'    => 'Binadik',
            'giatja'     => 'Giatja',
            'klinik'     => 'Klinik',
            'kamtib'     => 'Kamtib',
            default      => ucfirst($this->role),
        };
    }
}
