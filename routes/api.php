<?php

use App\Post;
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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});
 */
Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');

/*
hotel api routes
*/
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'API\AuthController@logout');

    Route::post('/hotel/create', 'HotelDetailsController@store');
    Route::get('/hotel/edit/{id}', 'HotelDetailsController@edit');
    Route::post('/hotel/update/{id}', 'HotelDetailsController@update');
    Route::delete('/hotel/delete/{id}', 'HotelDetailsController@delete');
    Route::get('/hotel', 'HotelDetailsController@index');

    Route::post('/booking/create', 'BookingController@store');
    Route::get('/booking/edit/{id}', 'BookingController@edit');
    Route::post('/booking/update/{id}', 'BookingController@update');
    Route::delete('/booking/delete/{id}', 'BookingController@delete');
    Route::get('/booking', 'BookingController@index');
    Route::post('/booking/range', 'BookingController@findByDate');

    Route::post('/pricelist/create', 'PriceListController@store');
    Route::get('/pricelist/edit/{id}', 'PriceListController@edit');
    Route::post('/pricelist/update/{id}', 'PriceListController@update');
    Route::delete('/pricelist/delete/{id}', 'PriceListController@delete');
    Route::get('/pricelist', 'PriceListController@index');

    Route::post('/roomtype/create', 'RoomTypeController@store');
    Route::get('/roomtype/edit/{id}', 'RoomTypeController@edit');
    Route::post('/roomtype/update/{id}', 'RoomTypeController@update');
    Route::delete('/roomtype/delete/{id}', 'RoomTypeController@delete');
    Route::get('/roomtype', 'RoomTypeController@index');

    Route::post('/roommanager/create', 'RoomManagerController@store');
    Route::get('/roommanager/edit/{id}', 'RoomManagerController@edit');
    Route::post('/roommanager/update/{id}', 'RoomManagerController@update');
    Route::delete('/roommanager/delete/{id}', 'RoomManagerController@delete');
    Route::get('/roommanager', 'RoomManagerController@index');
});
