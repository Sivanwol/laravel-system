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
        Route::group(['prefix' => '{business_id}/vehicle'], function () {
            Route::get('/list', 'VehicleController@index');
            Route::get('/{id}', 'VehicleController@show');
            Route::post('/', 'VehicleController@store');
            Route::put('/{id}', 'VehicleController@update');
            Route::post('/{id}/milage', 'VehicleController@updateMileage');
            Route::post('/{id}/status', 'VehicleController@updateStatus');
            Route::delete('/{id}', 'VehicleController@destroy');
            Route::delete('/{id}/unassign', 'VehicleController@removeFromBusiness');
            Route::delete('/unassign/all', 'VehicleController@removeAllFromBusiness');

        });
    });
});
