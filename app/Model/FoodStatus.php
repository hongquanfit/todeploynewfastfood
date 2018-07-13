<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodStatus extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'status',
    ];

    public function foods()
    {
        return $this->hasMany(App\Model\Food::class);
    }
}
