<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KataPositif extends Model
{
    protected $table = 'kata_positif';

    protected $fillable = [
        'kata',
    ];
}