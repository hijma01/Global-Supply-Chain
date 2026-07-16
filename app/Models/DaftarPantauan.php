<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarPantauan extends Model
{
    protected $table = 'daftar_pantauan';

    protected $fillable = [
        'pengguna_id', 'negara_id',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function negara()
    {
        return $this->belongsTo(Negara::class, 'negara_id');
    }
}