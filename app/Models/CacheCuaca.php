<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheCuaca extends Model
{
    protected $table = 'cache_cuaca';

    protected $fillable = [
        'negara_id', 'suhu', 'curah_hujan', 'kecepatan_angin',
        'tingkat_risiko_badai', 'dicatat_pada',
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class, 'negara_id');
    }
}