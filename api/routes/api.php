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
Route::group(['middleware' => 'session'], function (){
    Route::get('/products/{id}', 'ItemController@show');

    Route::group(['middleware' => ['auth:api']], function (){
        Route::get('/user', 'UserController@show');
        Route::put('/users/{id}', 'UserController@update');
        Route::resource('/examples', 'ExampleController');
        Route::resource('/places', 'PlaceController');
        Route::resource('/categories', 'CategoryController');
        Route::delete('/categories', 'CategoryController@massiveDestroy');
        Route::get('/sales/{id}/manage', 'SaleController@manage');
        Route::get('/sales/{id}/attempt', 'SaleController@attempt');
        Route::resource('/products', 'ItemController');

    });
    Route::resource('/sales', 'SaleController');
    Route::get('/sales/{sale}/category_items/{category}', 'SaleController@categoryItems');
    Route::post('/upload/{hash}', 'UploadController@upload');
    Route::get('/search', 'UserController@search');
    Auth::routes(['verify' => true]);
    Route::get('/crowler', 'CrowlerController@index');
});

