<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AddressRate extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'address_id',
    	'user_id',
    	'score',
    	'time',
    ];
}
