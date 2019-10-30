<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(){
        $items = Item::whereHas('place', function ($q){
            $q->where('user_id', Auth::id());
        })->with('categories', 'tags', 'place')->paginate(10);
        return response()->json($items);
    }
    public function store(Request $request){
        $request->request->set('user_id', Auth::id());
        if($request->place) $request->request->set('place_id', $request->place['id']);
        $request->request->set('active', true);
        $request->validate($this->validators($request));
        $item = Item::create($request->all());
        foreach ($request->categories as $category){
            if(is_array($category)){
                $cat = Category::where('id', $category['id'])->first();
            }else{
                if(!($cat = Category::where('place_id', $request->place_id)->where('name' , $category)->first())){
                    $cat = Category::create([
                        'name' => $category,
                        'active' => true,
                        'place_id' => $request->place_id
                    ]);
                }
            }
            if($cat) $item->categories()->attach($cat->id);
        }
        foreach ($request->tags as $tag){
            $arr = [];
            $arr['tag'] = $tag;
            $arr['model_name'] = 'item';
            $item->tags()->create($arr);
        }
        return response()->json($item->load('tags', 'categories'));
    }
    protected function validators($request){
        return [
            'place_id' => 'required|exists:places,id',
            'user_id' => ['required', function($field, $data, $fail)use($request){
                if($data != $request->place['user_id']) $fail('Nie masz uprawnień');
            }],
            'name' => 'required',
            'image' => ['required', function($field, $data, $fail){
                if(!file_exists(storage_path('/app/public/'.$data))) $fail('Plik nie istnieje');
            }],
            'price' => 'required',
            'categories' => 'array|required'
        ];
    }
}
