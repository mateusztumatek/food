<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ItemsService;

class ItemController extends Controller
{
    public function index(Request $request){
        $items = Item::whereHas('place', function ($q) use($request){
            $q->where('user_id', $request->user_id);
        })->with('categories', 'tags', 'place')->where(function ($q)use($request){
            if($request->term){
                $q->where('name', 'LIKE', '%'.$request->term.'%');
            }
        })->filter();

        if($request->paginate){
            $items = $items->paginate(($request->per_page)? $request->per_page : 10);
        }else $items = $items->get();
        return response()->json($items);
    }
    public function show(Request $request, $id){
        $product = Item::find($id);
        if(!$product) return response()->json(['message' => 'Brak produktu'], 404);
        return response()->json($product);
    }
    public function store(Request $request){
        $request->request->set('user_id', $request->user('api')->id);
        if($request->place) $request->request->set('place_id', $request->place['id']);
        $request->request->set('active', true);
        $request->validate($this->validators($request));
        $item = ItemsService::create($request);
        return response()->json($item->load('tags', 'categories'));
    }
    public function update(Request $request, $id){
        $item = Item::find($id);
        if(!Auth::check() || $item->place->user_id != Auth::id()) return response()->json(['message' => 'You dont have permission'], 403);
        $request->request->set('user_id',$request->user('api')->id);
        $this->Validate($request, $this->validators($request));
        $item->update($request->all());
        $item->tags()->delete();
        $item->categories()->detach();
        foreach ($request->tags as $tag){
            if(is_object($tag) || is_array($tag)){
                Tag::create($tag);
            }else{
                $arr = [];
                $arr['model_name'] = 'item';
                $arr['model_id'] = $item->id;
                $arr['tag'] = $tag;
                Tag::create($arr);
            }
        }

        foreach ($request->categories as $category){
            if(is_array($category) || is_object($category)){
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
