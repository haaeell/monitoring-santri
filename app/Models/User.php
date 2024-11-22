<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'foto'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function santri()
    {
        return $this->hasOne(Santri::class);
    }

    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    public function kepalaPondok()
    {
        return $this->hasOne(KepalaPondok::class);
    }

    public function waliSantri()
    {
        return $this->hasOne(WaliSantri::class);
    }
}
