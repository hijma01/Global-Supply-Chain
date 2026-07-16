<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negara extends Model
{
    protected $table = 'negara';

    protected $fillable = [
        'nama', 'kode_negara', 'kode_negara_3', 'kode_mata_uang',
        'nama_mata_uang', 'wilayah', 'sub_wilayah', 'ibu_kota',
        'populasi', 'url_bendera', 'lintang', 'bujur',
    ];

    public function dataEkonomi()
    {
        return $this->hasMany(DataEkonomiNegara::class, 'negara_id');
    }

    public function cacheCuaca()
    {
        return $this->hasMany(CacheCuaca::class, 'negara_id');
    }

    public function skorRisiko()
    {
        return $this->hasMany(SkorRisiko::class, 'negara_id');
    }

    public function pelabuhan()
    {
        return $this->hasMany(Pelabuhan::class, 'negara_id');
    }

    public function daftarPantauan()
    {
        return $this->hasMany(DaftarPantauan::class, 'negara_id');
    }
}