<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerbandinganNegara extends Model
{
    protected $table = 'perbandingan_negara';

    protected $fillable = [
        'pengguna_id', 'negara_a_id', 'negara_b_id', 'dibandingkan_pada',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function negaraA()
    {
        return $this->belongsTo(Negara::class, 'negara_a_id');
    }

    public function negaraB()
    {
        return $this->belongsTo(Negara::class, 'negara_b_id');
    }
}