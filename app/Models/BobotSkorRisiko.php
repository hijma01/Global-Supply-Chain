<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotSkorRisiko extends Model
{
    protected $table = 'bobot_skor_risiko';

    protected $fillable = [
        'bobot_cuaca', 'bobot_inflasi', 'bobot_berita', 'bobot_kurs',
    ];
}