<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = [
        'nama', 'email', 'password', 'peran',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function daftarPantauan()
    {
        return $this->hasMany(DaftarPantauan::class, 'pengguna_id');
    }

    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'pengguna_id');
    }

    public function perbandingan()
    {
        return $this->hasMany(PerbandinganNegara::class, 'pengguna_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'pengguna_id');
    }
}