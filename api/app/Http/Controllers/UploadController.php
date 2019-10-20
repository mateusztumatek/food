<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
class UploadController extends Controller
{
    public function upload(Request $request, $p){
        $files = [];
        if($request->allFiles()){
            if(array_key_exists('files', $request->allFiles())){
                $fs = $request->allFiles()['files'];
            }else{
                $fs = $request->allFiles();
            }
            foreach ($fs as $file){
                if(@is_array(getimagesize($file))){
                    $date = Carbon::now();
                    $image = $file;
                    $extension = $file->getClientOriginalExtension();
                    $filename = md5(Str::random(60)).'.'.$extension;
                    $path = 'storage/'.$p.'/'.$date->format('F').$date->format('Y').'/';
                    if(!file_exists(public_path('/storage/'.$p))) mkdir(public_path('/storage/'.$p));
                    if(!file_exists(public_path($path))) mkdir(public_path($path));
                    $img = Image::make($file->getRealPath());
                    if($request->width || $request->height){
                        if($request->height == null && $request->width != null){
                            $img->resize($request->width, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }elseif($request->height != null && $request->width == null){
                            $img->resize(null, $request->height, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }elseif($request->height && $request->width){
                            $img->resize($request->width, $request->height);
                        }
                    }
                    $img->save(public_path($path.'/'.$filename));
                    $to_save = $p.'/'.$date->format('F').$date->format('Y').'/'.$filename;
                    array_push($files, $to_save);
                }
            }
        }
        return response()->json(['data' => $files]);
    }
    public function remove(Request $request){

        if($request->path){
            if(!is_array($request->path)){
                if(file_exists(public_path('/storage/'.$request->path))){
                    unlink(public_path('/storage/'.$request->path));
                }
            }else{
                foreach ($request->path as $path){
                    if(file_exists(public_path('/storage/'.$path))){
                        unlink(public_path('/storage/'.$path));
                    }
                }
            }

        }else{
            return response()->json(['message' => 'Path not defined']);
        }


        return response()->json(true);
    }
}
