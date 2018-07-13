<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodType extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'type_id',
    	'food_id'
    ];
}
