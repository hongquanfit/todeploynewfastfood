<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodNutrition extends Model
{
    public $timestamps = false;
    protected $table = 'food_nutritions';
    protected $fillable = [
    	'volume',
    	'calorie'
    ];
}
