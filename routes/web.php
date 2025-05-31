<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\ProductGroupProductController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [IndexController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

// ProductGroup routes
Route::prefix('product-groups')->middleware(['auth', 'verified'])->group(function () {
    Route::post('/', [ProductGroupController::class, 'store'])->name('product-groups.store');
    Route::get('{id}', [ProductGroupController::class, 'show'])->name('product-groups.show');
    Route::put('{id}', [ProductGroupController::class, 'update'])->name('product-groups.update');
    Route::delete('{id}/{delete}', [ProductGroupController::class, 'destroy'])->name('product-groups.destroy');
});

// ProductGroupProduct routes
Route::prefix('product-group-products')->middleware(['auth', 'verified'])->group(function () {
    Route::post('/', [ProductGroupProductController::class, 'store'])->name('product-group-products.store');
    Route::post('/many', [ProductGroupProductController::class, 'storeMany'])->name('product-group-products.store-many');
    Route::delete('/many', [ProductGroupProductController::class, 'destroyMany'])->name('product-group-products.destroy-many');
    Route::delete('{categoryId}/{productId}', [ProductGroupProductController::class, 'destroy'])->name('product-group-products.destroy');
});

// Product routes
Route::prefix('products')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('{id}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::post('/reprocess/{id}', [ProductController::class, 'reprocess'])->name('products.reprocess');
    Route::delete('{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

Route::get('/test', [TestController::class, 'index'])->name('test.index');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
