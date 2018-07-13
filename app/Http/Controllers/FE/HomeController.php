<?php

namespace App\Http\Controllers\FE;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\Food;
use App\Model\Type;
use App\Model\Address;
use App\Model\Image;
use App\Model\FoodType;
use App\Model\Homepage;
use App\Model\Favorite;
use App\Model\Rate;
use App\Model\Nutrition;

class HomeController extends Controller
{
    public function index($typeId=null)
    {
        $food = Food::toGetListFood('total_score', 'DESC')->toArray();
        $foodNewest = Food::toGetListFood('create_at', 'DESC')->toArray();
        $favoriteFood = Favorite::where('user_id', Auth::user() ? Auth::user()->id : 'none')->get()->pluck('food_id')->toArray();

        if ($favoriteFood) {
            $favoriteList = Food::toGetListFood('total_score', 'DESC', $favoriteFood)->toArray();
        } else {
            $favoriteList = [];
        }
        $food = $this->renderArrayFood($food);
        $food = collect($food)->sortByDesc('avrRate')->toArray();
        $foodNewest = $this->renderArrayFood($foodNewest);
        $favoriteList = $favoriteList ? $this->renderArrayFood($favoriteList) : [];

        $data['headItem'] = [];
        $data['listItem'] = $food;
        $data['listType'] = Type::all()->toArray();
        $data['foodNewest'] = $foodNewest;
        $data['favoriteList'] = $favoriteList;
        $data['allowHeadBoard'] = false;
        $data['allowBottom'] = true;
        $data['allowFavorite'] = true;
        $data['allowListPanel'] = true;
        $data['allowAllPanel'] = false;
        
        return view('FE.home', $data);
    }

    public function showAll($type = null, $rate = null, $status = null)
    {
        $idType = $type ? explode('_', $type) : $type;
        $scoreRate = $rate ? explode('_', $rate) : $rate;
        $idStatus = $status ? explode('_', $status) : $status;
        $food = new Food();

        if ($idType) {
            $foodId = FoodType::whereIn('type_id', $idType)->get()->pluck('food_id')->toArray();
            $food = $food->whereIn('id', $foodId);
        }

        if ($scoreRate) {
            $food = $food->whereRaw('total_score >= rate_times*' . min($scoreRate) . ' AND total_score <= rate_times*' . max($scoreRate) . ' AND total_score > 0');
        }

        if ($idStatus) {
            $food = $food->whereIn('food_status_id', $idStatus);
        }
        $food = $food->toGetListFood('total_score', 'DESC', [], 9999999)->toArray();
        $food = $this->renderArrayFood($food);
        $data['listItem'] = $food;
        $data['listType'] = Type::all()->toArray();
        //options
        $data['typeValue'] = is_array($idType) ? $idType : [$idType];
        $data['rateScore'] = is_array($scoreRate) ? $scoreRate : [$scoreRate];
        $data['statusValue'] = is_array($idStatus) ? $idStatus : [$idStatus];
        $data['allowHeadBoard'] = false;
        $data['allowListPanel'] = false;
        $data['allowBottom'] = false;
        $data['allowFavorite'] = false;
        $data['allowAllPanel'] = true;
        
        return view('FE.home', $data);
    }

    public function renderArrayFood($food)
    {
        // pr($food);
        foreach ($food as $key => $item) {
            $food[$key]['rateStar'] = renderStar($item['total_score'], $item['rate_times']);
            $food[$key]['avrRate'] = ($item['rate_times'] == 0) ? 0 : $item['total_score'] / $item['rate_times'];
            $adr = $item['addresses'];

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

        return $food;
    }
    public function findItem($name = null)
    {
        $food = new Food;
        if ($name) {
            $food = $food->where('food', 'like', "%$name%");
        }
        
        $food = $food->toGetListFood('total_score', 'DESC', [], 999999)->toArray();
        $food = $this->renderArrayFood($food);
        $data['listItem'] = $food;
        $data['allowHeadBoard'] = false;
        $data['allowListPanel'] = false;
        $data['allowBottom'] = false;
        $data['allowFavorite'] = false;
        $data['allowAllPanel'] = true;
        $data['searchOldName'] = $name;
        $data['listType'] = Type::all()->toArray();
        
        return view('FE.home', $data);
    }
    public function searchByType($by = null, $id = null)
    {
        switch ($by) {
            case 'allrandom':
                return $this->allRandom();
                break;
            case 'withtype':
                return $this->onlyType(1, $id);
                break;
            case 'bystar':
                $data['listItem'] = $this->byStar($id);
                $data['listType'] = Type::all()->toArray();
                $data['allowHeadBoard'] = false;
                $data['allowBottom'] = false;
                $data['allowFavorite'] = false;
                $data['allowAllPanel'] = false;
                $data['allowListPanel'] = true;

                return view('FE.home', $data);
                break;
            case 'randomWithStar':
                $arr = $this->byStar($id);
                $rKey = array_rand($this->byStar($id));

                return json_encode($arr[$rKey]);
                break;
            case 'fromFavorite':
                return $this->fromFavorite();
                break;
            case 'withCalorie';
                return $this->withCalorie(1, $id);
                break;
            case 'fullitemcalorie':
                $type = Type::all()->toArray();
                foreach ($type as $key => $value) {
                    $newType[$value['id']] = $value['types'];
                }
                $data['listItem'] = $this->withCalorie(0, $id);
                $data['listType'] = Type::all()->toArray();
                $data['selectType'] = $newType;
                $data['allowHeadBoard'] = false;
                $data['allowBottom'] = false;
                $data['allowFavorite'] = false;
                $data['allowAllPanel'] = true;
                $data['allowListPanel'] = false;

                return view('FE.home', $data);
                break;
            case 'onlytype':
                $type = Type::all()->toArray();
                foreach ($type as $key => $value) {
                    $newType[$value['id']] = $value['types'];
                }
                $data['listItem'] = $this->onlyType(0, $id);
                // pr($data['listItem']);
                $data['listType'] = Type::all()->toArray();
                $data['selectType'] = $newType;
                $data['typeValue'] = explode('_', $id);
                $data['allowHeadBoard'] = false;
                $data['allowBottom'] = false;
                $data['allowFavorite'] = false;
                $data['allowAllPanel'] = true;
                $data['allowListPanel'] = false;

                return view('FE.home', $data);
                break;
        }
    }

    public function doSuggest(Request $req)
    {
        $req->merge([
            'user_id' => Auth::user()->id,
            'create_at' => time(),
        ]);
        
        if ($req->submit == 'insert') {
            try {
                $id = Food::create($req->all())->id;
            } catch (Exception $e) {

                return redirect('/')->with('fail', __('suggestFail'));
            }
            
            $food = Food::find($id);
            $food->types()->attach($req->food_type);

            if ($req->hasFile('hideImg')) {
                $ava = $req->file('hideImg');
                $name = $ava->getClientOriginalName();
                $name = time() . '_' . $name;
                $ava->move(config('app.imagesUrl'), $name);
                $image = new Image([
                    'url' => $name,
                ]);
                $food->images()->save($image);
            }
        }
        else {
            $id = explode('_', $req->submit)[1];
            $food = Food::find($id);
        }

        $groupAddress = ($req->address) ? $req->address : $req->price;
        $address = $req->address;
        $price = $req->price;

        if ($groupAddress) {
            for ($i = 0; $i < count($groupAddress); $i++) {
                $arrayAddress = [];
                $arrayAddress['address'] = $address[$i];
                $arrayAddress['price'] = $price[$i];
                $arrayAddress['user_id'] = Auth::user()->id;
                $arrayIdAddress[] = Address::create($arrayAddress)->id;
            }
        }

        $touch = $food->addresses()->attach($arrayIdAddress);
        foreach ($req->nutrition as $key => $value) {
            $findBackCalo = Nutrition::where('id', $value)->get()->toArray();
            $food->nutritions()->syncWithoutDetaching([
                $value => [
                    'calorie' => $findBackCalo[0]['calorie'],
                    'volume' => 100,
                ],
            ]);
        }
        if ($id) {
            $msg = [
                'type' => 'success',
                'id' => $id,
                'food' => $req->food,
                'user' => Auth::user()->name,
                'time' => $req->create_at,
                'image' => $name ? $name : '39079980-food-wallpapers.jpg',
                'message' => __('suggestSuccess'),
            ];
        } else {
            $msg = [
                'type' => 'error',
                'message' => __('Something wrong happened'),
            ];
        }
        return redirect('/')->with('message', $msg);
    }

    //function
    public function onlyType($type, $id)
    {
        $arrayId = $id ? explode('_', $id) : [];

        if ($arrayId) {
            $typeId = FoodType::whereIn('type_id', $arrayId)->get()->pluck('food_id')->toArray();
            $searched = Food::whereIn('id', $typeId)->whereHas('foodStatus', function($query){
                        $query->where('status', 'Displaying');
                    })
                    ->foodInfo('total_score', 'DESC')
                    ->toArray();

            $searched = $this->renderArrayFood($searched);
        }
        else {
            $searched = [];
        }

        if ($type) {
            return json_encode(array_random($searched));
        }
        else {
            return $searched;
        }
    }

    public function byStar($star)
    {
        $arrayStar = $star ? explode('_', $star) : [];
        $food = Food::whereRaw('total_score >= rate_times*' . min($arrayStar) . ' AND total_score <= rate_times*' . max($arrayStar) . ' AND total_score > 0')
            ->foodInfo('total_score', 'DESC')
            ->toArray();
        ;
        $food = $this->renderArrayFood($food);

        return $food;
    }

    public function withCalorie($allowRandom, $calo)
    {
        $calo = $calo / 3;
        $food = Food::whereBetween('total_calorie', [$calo - 100, $calo + 100])
                ->toGetListFood('total_score', 'DESC')->toArray();
        if (!$food) {
            return 0;
        }
        $food = $this->renderArrayFood($food);
        if ($allowRandom) {
            $rKey = array_rand($food);

            return json_encode($food[$rKey]);
        } else {
            return $food;
        }
       
    }
    public function fromFavorite()
    {
        $favoriteFood = Favorite::where('user_id', Auth::user() ? Auth::user()->id : 'none')->get()->pluck('food_id')->toArray();

        if ($favoriteFood) {
            $favoriteList = Food::toGetListFood('total_score', 'DESC', $favoriteFood)->toArray();
        } else {
            return 0;
        }

        $favoriteList = $this->renderArrayFood($favoriteList);
        $rKey = array_rand($favoriteList);

        return json_encode($favoriteList[$rKey]);

    }

    public function allRandom()
    {
        $searched = Food::whereHas('foodStatus', function($query){
                        $query->where('status', 'Displaying');
                    })
                    ->foodInfo('total_score', 'DESC')
                    ->toArray();

        $searched = $this->renderArrayFood($searched);

        return json_encode(array_random($searched));
    }


}
