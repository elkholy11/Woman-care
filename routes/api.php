<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthController as UserAuthController;
use App\Http\Controllers\Api\User\ProductController as UserProductController;
use App\Http\Controllers\Api\User\CategoryController as UserCategoryController;
use App\Http\Controllers\Api\User\BrandController as UserBrandController;
use App\Http\Controllers\Api\User\CartController as UserCartController;
use App\Http\Controllers\Api\User\OrderController as UserOrderController;
use App\Http\Controllers\Api\User\ReviewController as UserReviewController;
use App\Http\Controllers\Api\User\UserController as UserUserController;

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\CartController as AdminCartController;
use App\Http\Controllers\Api\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;



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
  Route::prefix('user')->group(function () {
    // Auth
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserAuthController::class, 'logout']);

        // User Profile
        Route::get('profile', [UserUserController::class, 'profile']);
        Route::put('profile', [UserUserController::class, 'updateProfile']);

        // Products
        Route::get('products', [UserProductController::class, 'index']);
        Route::get('products/{id}', [UserProductController::class, 'show']);

        // Categories
        Route::get('categories', [UserCategoryController::class, 'index']);
        Route::get('categories/{id}', [UserCategoryController::class, 'show']);

        // Brands
        Route::get('brands', [UserBrandController::class, 'index']);
        Route::get('brands/{id}', [UserBrandController::class, 'show']);

        // Carts
        Route::apiResource('cart', UserCartController::class);

        // Orders
        Route::apiResource('orders', UserOrderController::class);
        Route::post('orders/{order}/status', [UserOrderController::class, 'updateStatus']);

        // Reviews
        Route::apiResource('reviews', UserReviewController::class);
    });
});

Route::prefix('admin')->group(function () {

    // Auth
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);

        // Categories
        Route::apiResource('categories', AdminCategoryController::class);

        // Brands
        Route::apiResource('brands', AdminBrandController::class);

        // Products
        Route::apiResource('products', AdminProductController::class);

        // Orders
        Route::apiResource('orders', AdminOrderController::class);
        Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus']);

        // Carts
        Route::apiResource('carts', AdminCartController::class);

        // Reviews
        Route::apiResource('reviews', AdminReviewController::class);

        // Users management
        Route::apiResource('users', AdminUserController::class);
    });
});

