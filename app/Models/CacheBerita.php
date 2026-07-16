<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheBerita extends Model
{
    protected $table = 'cache_berita';

    protected $fillable = [
        'judul', 'deskripsi', 'url', 'sumber', 'kategori',
        'diterbitkan_pada', 'positive_score', 'negative_score', 'sentiment_label',
    ];
}