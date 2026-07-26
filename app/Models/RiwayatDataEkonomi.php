<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatDataEkonomi extends Model
{
    protected $table = 'riwayat_data_ekonomi';

    protected $fillable = [
        'negara_id',
        'pdb',
        'tingkat_inflasi',
        'nilai_ekspor',
        'nilai_impor',
        'tanggal'
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class);
    }
}