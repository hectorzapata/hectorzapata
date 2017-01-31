<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('bienvenida');
});
Route::post('app', 'appController@app');
Route::get('obtPosiciones', 'appController@obtPosiciones');
Route::get('nuevasPosiciones', 'appController@nuevasPosiciones');

Route::resource('api/actor', 'ApiController',
                ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
