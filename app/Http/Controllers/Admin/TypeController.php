<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Type as Mtype;

class TypeController extends Controller
{
    public function getType()
    {
        $listType = Mtype::all()->toArray();
        $listType = collect($listType);
        $data['listType'] = $listType->sortBy('orders')->values()->all();
        
        return view('Admin.Type', $data);
    }

    public function doEdit(Request $req)
    {
        $type = Mtype::find($req->id);
        if ($type) {
            $type->type_name = $req->type_name;
            $result = $type->save();
        } else {
            $result = Mtype::create($req->all());
        }
        
        return $result;
    }

    public function detectID(Request $req)
    {
        $id = $req->id;
        $rs = Mtype::find($id)->foods()->get()->toArray();
        
        return count($rs);
    }
    
    public function confirmDelete(Request $req)
    {
        $type = Mtype::find($req->typeId)->foods()->detach();
        try {
            Mtype::find($req->typeId)->delete();
        }
        catch (Exception $e) {
            return redirect('/admin/type/')->with('success', __('failRemove'));
        }        

        return redirect('/admin/type/')->with('success', __('successRemove'));
    }
    
    public function sort(Request $req)
    {
        $arrSTT = collect(json_decode($req->arr, true));
        $arrSTT->map(function($item, $key){
            $up = Mtype::find($item['id']);
            $up->orders = $item['orders'];
            $up->save();
        });
        
        return 1;
    }
}
