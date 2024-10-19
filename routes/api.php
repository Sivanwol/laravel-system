<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\VehicleController;

Route::prefix('common')->group(function () {
    Route::get('/supported/languages', [CommonController::class, 'getSupportedLanguages']);
    Route::get('/platform/settings', [CommonController::class, 'getPlatformSettings']);
    Route::get('/countries', [CommonController::class, 'getCountries']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('user')->group(function () {
        Route::get('/me', [UserController::class, 'me']);
        Route::get('/{id}', [UserController::class, 'profile']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::put('/{id}/languages', [UserController::class, 'updateLanguages']);
    });

    Route::group(['prefix' => 'business'], function () {
        Route::get('/list', [BusinessController::class, 'index']);
        Route::get('/{id}', [BusinessController::class, 'show']);
        Route::post('/', [BusinessController::class, 'store']);
        Route::put('/{id}', [BusinessController::class, 'update']);
        Route::delete('/{id}', [BusinessController::class, 'destroy']);

        Route::group(['prefix' => '{business_id}/vehicle'], function () {
            Route::get('/list', [VehicleController::class, 'index']);
            Route::get('/{id}', [VehicleController::class, 'show']);
            Route::post('/', [VehicleController::class, 'store']);
            Route::put('/{id}', [VehicleController::class, 'update']);
            Route::post('/{id}/milage', [VehicleController::class, 'updateMileage']);
            Route::post('/{id}/status', [VehicleController::class, 'updateStatus']);
            Route::delete('/{id}', [VehicleController::class, 'destroy']);
            Route::delete('/{id}/unassign', [VehicleController::class, 'removeFromBusiness']);
            Route::delete('/unassign/all', [VehicleController::class, 'removeAllFromBusiness']);
        });
    });
});
