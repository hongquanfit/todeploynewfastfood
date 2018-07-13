<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Model\Type;
use App\Model\History;
use App\Model\Nutrition;
use App\Model\Food;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(Request $req)
    {
        if (!$req->is('login')) {
            session([
                'oldAccessUrl' => $req->url()
            ]);
        }

        $type = Type::all()->toArray();
        foreach ($type as $key => $value) {
            $newType[$value['id']] = $value['types'];
        }

        $nutri = Nutrition::all()->toArray();

        $foodWating = Food::toGetListFood('create_at', 'DESC', 'Waiting', [], 99999999)->toArray();
        // pr($foodWating);
        view()->share('selectType', $newType);
        view()->share('listNutri', $nutri);
        view()->share('countFoodWaiting', count($foodWating));
        view()->share('foodWaiting', array_slice($foodWating, 0, 5, true));
    }

    public function processGetMethod($food_id, $auth)
    {
        $arr = [
            'action' => 'View',
            'time' => time(),
            'food_id' => $food_id,
            'user_id' => $auth->id,
        ];
        $change = History::create($arr)->id;
    }

    public function processPostMethod($action, $food_id, $user)
    {
        $arr = [
            'action' => $action,
            'time' => time(),
            'food_id' => $food_id,
            'user_id' => $user,
        ];
        $change = History::create($arr)->id;
    }
}
