<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\ProductGroupProductController;
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
    Route::delete('{id}', [ProductGroupController::class, 'destroy'])->name('product-groups.destroy');
});

// ProductGroupProduct routes
Route::prefix('product-group-products')->middleware(['auth', 'verified'])->group(function () {
    Route::post('/', [ProductGroupProductController::class, 'store'])->name('product-group-products.store');
    // Route::delete('{cate}', [ProductGroupProductController::class, 'destroy'])->name('product-group-products.destroy');
    Route::delete('{categoryId}/{productId}', [ProductGroupProductController::class, 'destroy'])->name('product-group-products.destroy');
});

// Product routes
Route::prefix('products')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    // Route::put('{id}', [ProductGroupController::class, 'update'])->name('products.update');
    Route::delete('{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
