<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterController::class);

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class);
});
