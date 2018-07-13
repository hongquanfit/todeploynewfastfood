<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id',
        'address',
        'price',
        'phone',
        'total_score',
        'rate_times',
        'avatar',
        'user_id',
    ];

    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }

    public function rates()
    {
        return $this->belongsToMany('App\User', 'address_rates');
    }
}
