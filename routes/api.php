<?php

use Illuminate\Http\Request;

Route::get('/', 'KritikController@index');

Route::post('login', 'Auth\LoginController@loginWithApi');
Route::get('data_nasabah', 'DataNasabah@data_nasabah');
Route::post('store', 'DataNasabah@store');
Route::get('data_user', 'DataNasabah@tampil_user');
Route::post('update', 'DataNasabah@update');
Route::post('hapus', 'DataNasabah@hapus');
Route::post('historycs', 'DataCsController@tampilcs');
Route::post('historyao', 'DataCsController@tampilao');
Route::post('historymitra', 'DataCsController@tampilmitra');
Route::post('historyreedem', 'DataCsController@historyreedem');
Route::post('historyreedemexternal', 'DataCsController@historyreedemexternal');
Route::post('historyao_ini', 'DataCsController@historyao_ini');
Route::post('historyreedemao_ini', 'DataCsController@historyreedemao_ini');
Route::post('historycs_ini', 'DataCsController@historycs_ini');
Route::post('historyreedemcs_ini', 'DataCsController@historyreedemcs_ini');
Route::post('historycs_admin', 'DataCsController@tampilcs_admin');
Route::post('historyao_admin', 'DataCsController@tampilao_admin');
Route::post('historymitra_admin', 'DataCsController@tampilmitra_admin');
Route::post('historyreedem_admin', 'DataCsController@historyreedem_admin');
Route::post('historyreedemexternal_admin', 'DataCsController@historyreedemexternal_admin');
Route::post('historyreedem_admin', 'DataCsController@historyreedem_admin');
Route::post('historyreedemexternal_admin', 'DataCsController@historyreedemexternal_admin');
Route::post('konfirmasi', 'DataCsController@konfirmasi');
Route::post('data_cs', 'DataCsController@cs');
Route::post('Ipointcs', 'DataCsController@inputcs');
Route::post('ambilcs', 'DataCsController@ambilcs');
Route::post('ambilmitra', 'DataCsController@ambilmitra');
Route::post('ambilnasabah', 'DataCsController@ambilnasabah');
Route::get('data_mitra', 'DataCsController@mitra');
Route::post('Ipointmitra', 'DataCsController@inputmitra');
Route::post('allpointcs', 'DataCsController@allpointcs');
Route::post('allpointmitra', 'DataCsController@allpointmitra');
Route::post('allpointao', 'DataCsController@allpointao');
Route::post('Imitra', 'DataCsController@inputdatamitra');
Route::post('detcs', 'DataCsController@detcs');
Route::post('detao', 'DataCsController@detao');
Route::post('detmitra', 'DataCsController@detmitra');
Route::post('reward', 'DataCsController@reward');
Route::post('hapusreward', 'DataCsController@hapusreward');
Route::post('pointcstambah', 'DataCsController@pointcstambah');
Route::post('pointcskurang', 'DataCsController@pointcskurang');
Route::post('reedemreward', 'DataCsController@reedemreward');
Route::post('Ireedem', 'DataCsController@inputreedem');
Route::post('setuju', 'DataCsController@setuju');
Route::post('tolak', 'DataCsController@tolak');
Route::post('disampaikan', 'DataCsController@disampaikan');
Route::post('thadiah', 'DataCsController@tambahhadiah');
Route::post('allunit', 'DataCsController@allunit');
Route::post('detunit', 'DataCsController@detunit');


Route::middleware('auth:api')->get('/user', function(Request $request){
    return $request->user();
});