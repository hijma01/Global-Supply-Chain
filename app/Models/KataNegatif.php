<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KataNegatif extends Model
{
    protected $table = 'kata_negatif';

    protected $fillable = [
        'kata',
    ];
}