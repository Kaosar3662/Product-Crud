<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::group(['prefix' => 'inventory', 'as' => 'products.'], function () {

    Route::get('/all', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/store', [ProductController::class, 'store'])->name('store');

    // Show/Edit/Delete using SLUG instead of ID
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product:slug}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product:slug}/update', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product:slug}/delete', [ProductController::class, 'destroy'])->name('destroy');
});
