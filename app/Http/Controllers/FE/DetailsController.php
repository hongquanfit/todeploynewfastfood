<?php

namespace App\Http\Controllers\FE;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Model\Food;
use App\Model\Type;
use App\Model\Comment;
use App\User;
use App\Model\Address;
use App\Model\AddressComment;
use App\Model\AddressRate;
use App\Model\Rate;

class DetailsController extends Controller
{
    public function show($info = null)
    {
        $foodId = explode('_', $info)[1];
        if (Auth::user()) {
            $this->processGetMethod($foodId, Auth::user());
        }
        
        $food = Food::toShowDetails($foodId)->with('rates:score,time,user_id')->get();
        $rate = Rate::where('food_id', $foodId)->selectRaw('count(`time`) as s, score')->groupBy('score')->orderBy('score','DESC')->get()->toArray();
        $countFoodScore = [];
        foreach ($rate as $key => $value) {
            $countFoodScore[$value['score']] = $value['s'];
        }

        foreach ($food as $key => $item) {
            $food[$key]['rateStar'] = renderStar($item['total_score'], $item['rate_times']);
            $food[$key]['countComment'] = count($item['comments']);
            $adr = $item['addresses']->toArray();

            if ($adr) {
                if (count($adr) == 1) {
                    $food[$key]['price'] = number_format($adr[0]['price'], 0) . ' VND';
                } else {
                    $food[$key]['price'] = number_format($adr[0]['price'], 0) . ' VND - ' . number_format($adr[count($adr) - 1]['price'], 0) . ' VND';
                }
            } else {
                $food[$key]['price'] = '???';
            }
        }

        $listAddress = $food->toArray()[0]['addresses'];
        
        foreach ($listAddress as $key => $adr) {
            $listAddress[$key]['rateStar'] = renderStar($adr['total_score'], $adr['rate_times']);
            $getUser = User::find($adr['user_id']);
            $adrComt = AddressComment::where('address_id', $adr['id'])->with('user')->get()->toArray();
            $countScoreAdr = AddressRate::where('address_id', $adr['id'])->selectRaw('count(`time`) as s, score')->groupBy('score')->orderBy('score','DESC')->get()->toArray();
            $listAddress[$key]['whoAdded'] = $getUser ? $getUser->toArray() : [];
            $listAddress[$key]['countAdrComt'] = count($adrComt);
            $listAddress[$key]['adrComment'] = collect($adrComt)->sortByDesc('time')->toArray();

            foreach ($adrComt as $adrComtKey => $adrComtValue) {
                $tempAdrComt = AddressRate::where([
                    'address_id' => $adrComtValue['address_id'],
                    'user_id' => $adrComtValue['user_id'],
                ])->get()->toArray();
                $listAddress[$key]['adrComment'][$adrComtKey]['score'] = $tempAdrComt ? $tempAdrComt[0]['score'] : 0;
            } 
            
            $newCountScoreAdr = [];

            foreach ($countScoreAdr as $kCSA => $vCSA) {
                $newCountScoreAdr[$vCSA['score']] = $vCSA['s'];
            }
            $listAddress[$key]['countScore'] = $newCountScoreAdr;
        }

        $arrFood = $food->toArray()[0];

        foreach ($arrFood['comments'] as $k1 => $comt) {
            foreach ($arrFood['rates'] as $k2 => $val) {
                if ($comt['user_id'] == $val['user_id']) {
                    $arrFood['comments'][$k1]['rate'] = $val['score'];
                }
            }
            $arrFood['comments'][$k1]['whoCommented'] = User::where('id', $comt['user_id'])->get()->toArray()[0]['name'];
        }

        $type = Type::all()->toArray();
        $data['headItem'] = $arrFood;
        $data['listType'] = $type;
        $data['headAddress'] = $listAddress;
        $data['currency'] = [
            'VND',
            'USD',
        ];
        $data['listComments'] = collect($arrFood['comments'])->sortByDesc('time')->toArray();
        $data['countScore'] = $countFoodScore;

        return view('FE.Details', $data);
    }

    public function addAddress(Request $req)
    {
        $this->processPostMethod('addAddress', $req->food, Auth::user()->id);
        $req->merge([
            'user_id' => Auth::user()->id,
        ]);

        if ($req->hasFile('adrAvatar')) {
            $ava = $req->file('adrAvatar');
            $name = $ava->getClientOriginalName();
            $name = time() . '_' . $name;
            $ava->move(config('app.imagesUrl'), $name);
            $req->merge([
                'avatar' => $name,
            ]);
        } else {
            $req->merge([
                'avatar' => '',
            ]);
        }
        
        $id = Address::create($req->all())->id;

        try {
            $change = Food::findOrFail($req->food)->addresses()->attach($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('fail', __('suggestFail'));
        }

        return back()->with('success', __('suggestSuccess'));
    }

    public function addComment(Request $req)
    {
        $req->merge([
            'user_id' => Auth::user()->id,
            'time' => time(),
        ]);
        
        if ($req->cmtType == 'food') {
            $this->processPostMethod('Comment', $req->food_id, Auth::user()->id);
            $resID = Comment::create($req->all())->id;
        } else {
            $resID = AddressComment::create($req->all())->id;
        }
        
        if ($resID) {
            if ($req->cmtType == 'food') {
                $voted = Rate::where([
                    'food_id' => $req->food_id,
                    'user_id' => $req->user_id,
                ])->get()->toArray();
            } else {
                $voted = AddressRate::where([
                    'address_id' => $req->address_id,
                    'user_id' => $req->user_id,
                ])->get()->toArray();
            }
            
            $resArr = [
                'who' => User::find($req->user_id)->toArray()['name'],
                'voted' => $voted ? $voted[0]['score'] : 0,
                'time' => date('H:i, d/m/Y', $req->time),
                'comment' => $req->comment,
            ];

            return json_encode($resArr);
        }
    }
}
