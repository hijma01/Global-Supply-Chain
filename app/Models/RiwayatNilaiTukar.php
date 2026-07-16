<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatNilaiTukar extends Model
{
    protected $table = 'riwayat_nilai_tukar';

    protected $fillable = [
        'kode_mata_uang', 'nilai_kurs', 'tanggal',
    ];
}