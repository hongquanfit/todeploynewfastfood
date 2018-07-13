<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'url', 
    	'food_id',
    ];

    public function food()
    {
        return $this->belongsTo(App\Model\Food::class);
    }
}
