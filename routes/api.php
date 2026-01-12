<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\APICategoryController;


Route::prefix('inventory')->group(function () {
    Route::get('/all', [APIProductController::class, 'index']);
    Route::post('/store', [APIProductController::class, 'store']);
    Route::put('/{product:slug}/update', [APIProductController::class, 'update']);
    Route::delete('/{product:slug}/delete', [APIProductController::class, 'destroy']);
});

Route::get('/categories', [APICategoryController::class, 'show']);
