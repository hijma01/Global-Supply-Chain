<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheBerita extends Model
{
    protected $table = 'cache_berita';

    protected $fillable = [
        'judul',
        'deskripsi',
        'url',
        'sumber',
        'kategori',
        'diterbitkan_pada',
        'skor_positif',
        'skor_negatif',
        'label_sentimen',
    ];

    protected $casts = [
        'diterbitkan_pada' => 'datetime',
    ];
}