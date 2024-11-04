<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::group(['prefix' => 'sign'], function () {
        Route::post('in', 'signIn');
        Route::post('up', 'signUp');
        Route::post('out', 'signOut');
    });
    Route::get('what-is-my-platform', 'whatIsMyPlatform'); // returns 'platform: mobile!'
});
