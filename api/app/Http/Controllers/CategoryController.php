<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::whereHas('place', function ($q){
            $q->where('user_id', Auth::id());
        })->get();
        return response()->json($categories);
    }
}
