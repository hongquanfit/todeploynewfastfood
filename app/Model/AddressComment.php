<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AddressComment extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'comment', 
        'time', 
        'user_id', 
        'address_id',
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function address()
    {
    	return $this->belongsTo(Address::class);
    }
}
