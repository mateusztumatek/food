<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request, $id){
        $request->validate([
            'avatar' => ['string', function($field, $data, $fail){
                if(!file_exists(public_path('/storage/'.$data))) $fail('File not exist');
            }]
        ]);
        $user = User::find($id);
        if(!$user) return response()->json(['message' => 'User not found'], 404);
        $user->image = $request->avatar;
        $request->request->set('image', $request->avatar);
        $user->update($request->all());
        return response()->json($user);
    }
    public function search(Request $request){
        $users = User::where('name', 'LIKE', '%'.$request->term.'%')->orWhere('login', 'LIKE', '%'.$request->term.'%')->paginate(10);
        return response()->json($users);
    }
    public function show(Request $request){
        return response()->json($request->user()->load('places'));
    }
}
