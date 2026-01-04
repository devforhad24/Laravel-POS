<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'login']);
Route::post('/login_post', [AuthController::class, 'login_post']);

Route::group(['middleware' => 'admin'], function () {
    // Protected routes go here
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard']);

    /* Category Management */
    Route::get('/admin/category', [CategoryController::class, 'index']);
    Route::get('/admin/category/data', [CategoryController::class, 'getCategories']);
    Route::post('/admin/category/store', [CategoryController::class, 'store']);
    Route::get('/admin/category/edit/{id}', [CategoryController::class, 'edit']);
    Route::put('/admin/category/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/admin/category/delete/{id}', [CategoryController::class, 'destroy']);

    /* Product Management */
    Route::get('/admin/product', [ProductController::class, 'productIndex']);
    Route::post('/admin/product/store', [ProductController::class, 'store'])->name('product.store');



});
Route::group(['middleware' => 'user'], function () {
    // Protected routes go here
    Route::get('/user/dashboard', [DashboardController::class, 'dashboard']);
});

Route::get('logout',[AuthController::class,'logout']);

