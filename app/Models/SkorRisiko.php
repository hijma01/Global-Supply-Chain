<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkorRisiko extends Model
{
    protected $table = 'skor_risiko';

    protected $fillable = [
        'negara_id', 'skor_cuaca', 'skor_inflasi', 'skor_kurs',
        'skor_sentimen_berita', 'skor_total', 'tingkat_risiko', 'dihitung_pada',
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class, 'negara_id');
    }
}