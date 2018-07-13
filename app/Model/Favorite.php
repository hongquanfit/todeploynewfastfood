<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'use_id',
        'food_id',
    ];
}
