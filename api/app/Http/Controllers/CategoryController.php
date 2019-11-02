<?php

namespace App\Http\Controllers;

use App\Category;
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

        if($request->paginate){
            $categories = $categories->paginate(($request->itemsPerPage)? $request->itemsPerPage : 10);
        }else{
            $categories = $categories->get();
        }
        return response()->json($categories);
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
}
