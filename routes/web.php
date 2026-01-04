<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'login']);
Route::post('/login_post', [AuthController::class, 'login_post']);

Route::group(['middleware' => 'admin'], function () {
    // Protected routes go here
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard']);
});
Route::group(['middleware' => 'user'], function () {
    // Protected routes go here
    Route::get('/user/dashboard', [DashboardController::class, 'dashboard']);
});

Route::get('logout',[AuthController::class,'logout']);

