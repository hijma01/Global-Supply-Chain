<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelabuhan extends Model
{
    protected $table = 'pelabuhan';

    protected $fillable = [
        'nama_pelabuhan', 'negara_id', 'lintang', 'bujur',
        'ukuran_pelabuhan', 'tipe_pelabuhan',
    ];

    protected $casts = [

        'lintang'=>'float',
        'bujur'=>'float',

    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class, 'negara_id');
    }
}