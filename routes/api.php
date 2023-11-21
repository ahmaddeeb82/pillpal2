<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* ----------------- Authentication Routes ----------------- */
Route::middleware('ApiLocalize')->group(function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class,'logout']);
        Route::post('addOrder', [OrderController::class,'createOrder']);
        Route::get('orders', [OrderController::class,'userOrders']);
        Route::get('orderDetails', [OrderController::class,'orderDetails']);
    });
});

