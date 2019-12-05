<?php

namespace App\Http\Controllers;

use App\DiscountCode;
use App\Sale;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index(Request $request){
        $user = $request->user('api');
        $places = $user->places;
        $sellouts = Sale::where(function ($q)use($places){
            foreach ($places as $p){
                $q->orWhere('place_id', $p->id);
            }
        })->get();
        $discounts = DiscountCode::where(function ($q)use ($places){
            foreach($places as $p){
                $q->orWhere('place_id', $p->id);
            }
        })->orWhere(function ($q)use($sellouts){
            foreach ($sellouts as $s){
                $q->orWhere('sellout_id', $s->id);
            }
        })->with('sellout', 'place')->paginate();
        return response()->json($discounts);
    }
}
