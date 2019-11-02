<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaceController extends Controller
{

    public function index(Request $request){
        $places = Place::filter()->with('tags')->get();
        return response()->json($places);
    }
    public function store(Request $request){
        if(!Auth::check()) return response()->json(['message' => 'User not login'], 404);
        $request->request->set('user_id', Auth::id());
        $request->validate($this->validation());
        $request->request->set('slug', $this->getSlug($request));
        $place = Place::create($request->all());
        foreach ($request->tags as $tag){
            $arr = array();
            $arr['model_name'] = 'place';
            $arr['tag'] = $tag;
            $place->tags()->create($arr);
        }
        return response()->json($place->load('tags'));
    }
    public function show(Request $request, $id){
        return response()->json(Place::with('tags')->find($id));
    }
    public function update(Request $request, $id){
        if(!Auth::check()) return response()->json(['message' => 'User not login'], 404);
        $request->request->set('user_id', Auth::id());
        $request->validate($this->validation());
        $place = Place::find($id);
        if($place->user_id != Auth::id()) return response()->json(['message' => 'You dont have permission'], 404);
        $request->request->set('slug', $this->getSlug($request));
        $place->update($request->all());
        $place->tags()->delete();
        foreach ($request->tags as $tag){
            $arr = array();
            $arr['model_name'] = 'place';
            $arr['tag'] = $tag;
            $place->tags()->create($arr);
        }

        return response()->json($place->load('tags'));
    }
    public function validation(){
        return[
            'name' => 'required',
            'user_id' => 'required|exists:users,id',
            'lng' => 'required',
            'lat' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'street' => 'required',
            'image' => ['required', function($field, $data, $fail){
                if($data && !file_exists(storage_path('app/public/'.$data))) $fail('File not exist');
            }]
        ];
    }
    public function destroy(Request $request, $id){
        $place = Place::find($id);
        if(!Auth::check() || $place->user_id != Auth::id()) return response()->json(['error' => 'You dont have permission'], 403);
        $place->products->delete();
        $place->tags()->delete();
        $place->delete();
        return response()->json(true);
    }
    protected function getSlug($request){
        $check = false;
        $i = 0;
        while (!$check){
            ($i == 0)? $add = '' : $add = $i;
            $slug = Helper::slugify($request->name.''.$add);
            (Place::where('slug', $slug)->first())? $check = false : $check = true;
            $i = $i + 1;
        }
        return $slug;
    }
}
