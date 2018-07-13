<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'food', 
        'description', 
        'city', 
        'total_score',
        'total_calorie',
        'rate_times',
        'user_id',
        'create_at',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function foodStatus()
    {
        return $this->belongsTo(FoodStatus::class);
    }
    
    public function foodUser()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'food_types');
    }

     public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_foods');
    }

    public function nutritions()
    {
        return $this->belongsToMany(Nutrition::class, 'food_nutritions')->withPivot('volume', 'calorie');
    } 

    public function rates()
    {
        return $this->belongsToMany('App\User', 'rates')->withPivot('score', 'time');
    }

    public function favorites()
    {
        return $this->belongsToMany('App\User', 'favorites');
    }

    //scope
    public function scopeToShowDetails($query, $foodId)
    {
        return $query->where('id', $foodId)->with([
            'types',
            'addresses',
            'images',
            'foodStatus',
            'foodUser',
            'comments',
        ])->with(['favorites' => function($query){
            $query->where('user_id', auth()->user() ? auth()->user()->toArray()['id'] : 'nobody');
        }]);
    }

    public function scopeToGetListFood($query, $orderCol, $orderType, $status = 'Displaying', $byList = [], $take = 13)
    {
        if ($byList) {
            $query = $query->whereIn('id', $byList);
        }

        return $query->whereHas('foodStatus', function($query) use ($status) {
                        $query->where('status', $status);
                    })->orderBy($orderCol, $orderType)
                    ->with('types:types')
                    ->with('addresses')
                    ->with('images')
                    ->with('foodUser')
                    ->with(['favorites' => function($query){
                        $query->where('user_id', auth()->user() ? auth()->user()->toArray()['id'] : 'nobody');
                    }])
                    ->take($take)
                    ->get();
    }

    public function scopeFoodInfo($query, $orderCol, $orderType)
    {
        return $query->orderBy($orderCol, $orderType)
                    ->with('types:types')
                    ->with('addresses')
                    ->with('images')
                    ->with(['favorites' => function($query){
                        $query->where('user_id', auth()->user() ? auth()->user()->toArray()['id'] : 'nobody');
                    }])
                    ->take(config('app.limitHomepage'))
                    ->get();
    }
}
