<?php

use App\Http\Controllers\Dashboard\Auth\AuthController;
use App\Http\Controllers\Dashboard\Home\HomeController;
use App\Http\Controllers\Dashboard\User\UserController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', [AuthController::class, '_login'])->name('_login');

        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('/');
        Route::resource('users', UserController::class);
    });
});
