<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ExampleController extends Controller
{
    public function langs(){
        $langs = config('app.locales');
        $remember = App::getLocale();
        $array = array();
        foreach ($langs as $lang){
            App::setLocale($lang);
            $array[$lang] = Lang::get('my');
        }
        App::setLocale($remember);
        return response()->json($array);
    }
}
