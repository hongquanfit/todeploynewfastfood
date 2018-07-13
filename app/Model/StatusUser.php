<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StatusUser extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
