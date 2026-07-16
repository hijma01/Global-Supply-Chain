<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiTukar extends Model
{
    protected $table = 'nilai_tukar';

    protected $fillable = [
        'mata_uang_dasar', 'mata_uang_tujuan', 'nilai_kurs', 'dicatat_pada',
    ];
}