<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $fillable = [
        'name', 
        'email', 
        'password'
    ];
    protected $hidden = [
        'password'
    ];

    public function role()
    {
        return $this->belongsTo(App\Model\Role::class);
    }

    public function statusUser()
    {
        return $this->belongsTo(App\Model\StatusUser::class);
    }

    public function comments()
    {
        return $this->hasMany(App\Model\Comment::class);
    }

    public function foodUsers()
    {
        return $this->hasMany(App\Model\Food::class, 'user_id');
    }

    public function foods()
    {
        return $this->belongsToMany(App\Model\Role::class);
    }

    public function addresses()
    {
        return $this->belongsToMany(App\Model\Address::class, 'address_rates');
    }
}
