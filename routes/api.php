<?php

use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UpdateAuthenticatedUserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', AuthenticatedUserController::class);
    Route::put('me', UpdateAuthenticatedUserController::class);
    Route::post('logout', LogoutController::class);

    Route::prefix('v1')->group(function () {
        Route::apiResource('products', ProductController::class);
    });
});
