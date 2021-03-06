<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['auth:api']], function (){
    Route::get('/user', function (Request $request){return $request->user();});
    Route::put('/users/{id}', 'UserController@update');
    Route::resource('/examples', 'ExampleController');
});
Route::post('/upload/{hash}', 'UploadController@upload');
Route::get('/search', 'UserController@search');
Auth::routes(['verify' => true]);
Route::get('/crowler', 'CrowlerController@index');
