<?php

namespace App\Http\Controllers\FE;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Model\Food;
use App\Model\Rate;
use App\Model\Comment;
use App\Model\Address;
use App\Model\AddressRate;

class RatingController extends Controller
{
    public function rateFood(Request $req)
    {
        $ss = Auth::user();
        if ($req->voteType == 'food') {
            $table = Food::find($req->toRateItem);
            $this->processPostMethod('Rate_' . $req->rate, $req->toRateItem, Auth::user()->id);
        } else {
            $table = Address::find($req->toRateItem);
        }
        
        $change = $table->rates()->syncWithoutDetaching([
            $ss->id => [
                'score' => $req->rate,
                'time' => time(),
            ],
        ]);

        if ($req->voteType == 'food') {
            $allScore = Rate::where('food_id', $req->toRateItem)->get();
        } else {
            $allScore = AddressRate::where('address_id', $req->toRateItem)->get();
        }
        
        $table->total_score = $allScore->sum('score');
        $table->rate_times = count($allScore);
        $table->save();

        $returnArray = [
            'total_score' => $allScore->sum('score'),
            'rate_times' => count($allScore),
            'star' => renderStar($allScore->sum('score'), count($allScore)),
        ];
        
        if ($change > 0) {
            return json_encode($returnArray);
        } else {
            return 0;
        }
    }

    public function addFavorite(Request $req)
    {
        $food = Food::find($req->id);
        if ($req->type == 'like') {
            $rs = $food->favorites()->detach(Auth::user()->id);
        } else {
            $rs = $food->favorites()->syncWithoutDetaching(Auth::user()->id);
        }

        return $rs;
    }

    public function comment(Request $req)
    {
        $req->merge([
            'food_id' => $req->foodId,
            'time' => time(),
            'user_id' => Auth::user()->id,
        ]);
        $result = Comment::create($req->all())->wasRecentlyCreated;
        return (int) $result;
    }
}
