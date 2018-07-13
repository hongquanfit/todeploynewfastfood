<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'role',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
