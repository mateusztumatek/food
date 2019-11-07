<?php

namespace App\Services;

use App\Category;
use App\Item;

class ItemsService{
    public static function create($request){
       /* if(is_object($request) && get_class($request) == 'Illuminate\Http\Request'){
            $data = $request->all();
        }else $data = $request;*/
        $item = Item::create([
            'place_id' => $request->place_id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'price' => $request->price,
            'active' => $request->active
        ]);
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
        return $item;
    }
}