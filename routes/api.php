<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ReviewController;

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



//  Auth Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

//  Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('logout', [AuthController::class, 'logout']);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Brands
    Route::apiResource('brands', BrandController::class);

    // Products
    Route::apiResource('products', ProductController::class);

    // Orders
    Route::apiResource('orders', OrderController::class);
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus']); // لتحديث حالة الطلب

    // Carts
    Route::get('cart',         [CartController::class, 'index']);
    Route::post('cart',        [CartController::class, 'store']);
    Route::put('cart/{id}',    [CartController::class, 'update']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);
    // Route::put('cart/{id}/decrease', [CartController::class, 'decreaseQuantity']);

    // Reviews
    Route::apiResource('reviews', ReviewController::class);
});
