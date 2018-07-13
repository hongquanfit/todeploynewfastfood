<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'action',
    	'time',
    	'user_id',
    	'address_id',
    	'food_id',
    ];
}
