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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
    Route::get('geolocation','Api\GeolocationController@geoCode');
    Route::get('geolocation/{id_address}','Api\GeolocationController@geoCodeByIp');
    Route::get('weather','Api\GeolocationController@weather');
    Route::get('weather/{id_address}','Api\GeolocationController@weatherByIp');