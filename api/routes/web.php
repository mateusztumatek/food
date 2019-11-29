<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify');
Route::resource('cart', 'CartController');
Route::post('/cart/{sale_id}', 'CartController@addItem');
Route::post('/cart/{sale_id}/delete', 'CartController@deleteItem');
Route::post('/cart/{sale_id}/update', 'CartController@updateItem');
Route::group(['middleware' => ['auth:api']], function () {
    Route::put('/orders/{id}', 'OrderController@update');
    Route::get('/orders/{sale_id}/sale', 'OrderController@getSelloutOrders');
    Route::get('/orders/customers', 'OrderController@getUserCustomersOrders');

});
Route::get('/orders/{hash}', 'OrderController@show');
Route::resource('orders', 'OrderController');
Route::post('orders/notify', 'OrderController@notify');
Route::get('sellout/{id}/qr', 'SaleController@qr');
Route::get('sellout/{id}/pdf', 'SaleController@pdf');
