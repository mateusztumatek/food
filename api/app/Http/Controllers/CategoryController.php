<?php

namespace App\Http\Controllers;

use App\Category;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request){
        if($request->sortBy){
            $request->request->set('orderBy', $request->sortBy[0]);
            if($request->sortDesc[0] == 'true'){
                $request->request->set('orderType', 'desc');
            }else{
                $request->request->set('orderType', 'asc');
            }
        }
        $categories = Category::whereHas('place', function ($q){
            $q->where('user_id', Auth::id());
        })->with('place')->filter()->where(function ($q)use($request){
            if($request->term){
                $q->where('name', 'LIKE', '%'.$request->term.'%');
            }
        });
        if($request->withProducts) $categories = $categories->withProducts();
        if($request->paginate){
            $categories = $categories->paginate(($request->itemsPerPage)? $request->itemsPerPage : 10);
        }else{
            $categories = $categories->get();
        }
        return response()->json($categories);
    }
    public function store(Request $request){
        $request->validate($this->validations());
        $cat = Category::create($request->all());
        return response()->json($cat->load('place'));
    }
    public function massiveDestroy(Request $request){
        foreach ($request->ids as $id){
            $category = Category::find($id);
            if($category){
                $category->items()->detach();
                $category->delete();
            }
        }
        return response()->json(true);
    }
    public function update(Request $request, $id){
        $category = Category::find($id);
        if(!Auth::check() || $category->place->user_id != Auth::id()) return response()->json(['message' => 'Nie masz uprawnień'], 403);
        $request->validate($this->validations());
        $category->update($request->all());
        return response()->json($category->load('place'));
    }
    public function validations(){
        return [
            'place_id' => 'required|exists:places,id',
            'name' => 'required',
            'image' => [function($field, $data, $fail){
                if(!file_exists(storage_path('app/public/'.$data))) $fail('Plik nie istnieje');
            }]
        ];
    }
}
