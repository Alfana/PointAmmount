<?php

use Illuminate\Http\Request;

Route::get('/', 'KritikController@index');

Route::post('login', 'Auth\LoginController@loginWithApi');
Route::get('data_nasabah', 'DataNasabah@data_nasabah');
Route::post('store', 'DataNasabah@store');

Route::middleware('auth:api')->get('/user', function(Request $request){
    return $request->user();
});