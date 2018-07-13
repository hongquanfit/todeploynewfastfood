<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'score', 
    	'time',
    ];
}
