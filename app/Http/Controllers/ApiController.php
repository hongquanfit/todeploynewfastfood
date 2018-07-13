<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Food;
use App\Model\Address;
use App\Model\Nutrition;

class ApiController extends Controller
{
    public function findFood($food)
    {
        $result['listFood'] = Food::where('food', 'like', "%$food%")->with([
            'addresses',
            'images',
        ])->get();
        $result['nutri'] = $this->detectIngredient($food);

        return $result;
    }

    public function findAddress($address)
    {
        return Address::where('address', 'like', "%$address%")->get();
    }

    public function detectIngredient($name)
    {
        $explName = explode(' ', $name);
        $nutri = [];
        if ($explName) {
            foreach ($explName as $key => $value) {
                $rs[] = Nutrition::where('name', 'like', "%$value%")->select(['id', 'name'])->get()->toArray();
            }

            $rs = call_user_func_array('array_merge', $rs);

            foreach ($rs as $key => $value) {
                $nutri[$value['id']] = $value['name'];
            }
        }
        if ($nutri) {
            foreach ($explName as $key => $name) {
                foreach ($nutri as $key => $food) {
                    similar_text($name, $food, $matchingPercentage);
                    $almostCorrect[$key] = $matchingPercentage;
                }
                $kNutri[] = array_keys($almostCorrect, max($almostCorrect));
            }
            $arr['nutri'] = call_user_func_array('array_merge', $kNutri);

            return json_encode($arr);
        }

        return '';        
    }
}
