<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'business'], function () {
        Route::get('/list', 'BusinessController@index');
        Route::get('/{id}', 'BusinessController@show');
        Route::post('/', 'BusinessController@store');
        Route::put('/{id}', 'BusinessController@update');
        Route::delete('/{id}', 'BusinessController@destroy');
    });
});