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
Route::group(array('prefix' => 'api/v1/'), function()
{

    Route::get('/', function () {
        return response()->json(['message' => 'API Truckpad']);
    });

    //rotas de Morista
    Route::post('driver', 'DriversController@createDriver');

    Route::put('driver/{id}', 'DriversController@updateDriver');

    Route::get('driver/{cpf}', 'DriversController@searchDriversByCpf');

    Route::get('drivers', 'DriversController@searchDriversOwnervehicle');

    //rotas de checkin
    Route::post('checkin', 'TerminalCheckinController@createCheckin');

    Route::get('checkin/carregado', 'TerminalCheckinController@searchCheckin');

    Route::get('checkins/origemDestino', 'TerminalCheckinController@searchCheckinSourceAndDestiny');

});

Route::get('/', function () {
    return redirect('api/v1');
});