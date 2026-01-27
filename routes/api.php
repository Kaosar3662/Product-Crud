<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\APICategoryController;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::prefix('products')->middleware('auth:sanctum')->group(function () {
    Route::get('/all', [APIProductController::class, 'index'])->middleware('throttle:60,1');
    Route::post('/store', [APIProductController::class, 'store']);
    Route::post('/{product:slug}/update', [APIProductController::class, 'update']);
    Route::delete('/{product:slug}/delete', [APIProductController::class, 'destroy']);
    Route::get('/{product:slug}', [APIProductController::class, 'show']);
});

Route::get('/categories', [APICategoryController::class, 'show']);
