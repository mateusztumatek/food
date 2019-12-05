<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCodeService{
    public function store($data){
        $validation = Validator::make($data, [
            'max_uses' => 'required|min:1',
        ]);
        if($validation->fails()) return respones()->json(['errors' => $validation->errors()]);
    }
}