<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'banner',
        'middle',
        'bottom',
    ];
}
