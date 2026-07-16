<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataEkonomiNegara extends Model
{
    protected $table = 'data_ekonomi_negara';

    protected $fillable = [
        'negara_id', 'pdb', 'tingkat_inflasi', 'nilai_ekspor', 'nilai_impor', 'tahun',
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class, 'negara_id');
    }
}