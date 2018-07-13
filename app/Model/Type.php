<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'types', 
    	'orders', 
    	'in_use',
    ];

    public function foods()
    {
        return $this->belongsToMany(Food::class, 'food_types');
    }
}
