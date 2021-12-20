<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;


//Admin:
Route::apiResource('/brands', BrandController::class);
Route::get('/brands/{brand}/products', [BrandController::class, "getProducts"]);

Route::apiResource('/category', CategoryController::class);
Route::get('/category/{category}/products', [CategoryController::class, "getProducts"]);
Route::get('/category/{category}/parent', [CategoryController::class, "parent"])->name('category.parent');
Route::get('/category/{category}/children', [CategoryController::class, "children"])->name('category.children');

Route::apiResource('/product', ProductController::class);
Route::apiResource('/product.gallery', GalleryController::class);
