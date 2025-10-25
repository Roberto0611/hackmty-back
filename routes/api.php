<?php

use App\Http\Controllers\DiscountsController;
use App\Http\Controllers\placesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoutesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JWTAuthController;


Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('user', [JWTAuthController::class, 'me']);
    Route::post('logout', [JWTAuthController::class, 'logout']);
    
    // rutas 
        // make a verify token route
        Route::post('verify-token', [JWTAuthController::class, 'verifyToken']);
});

// rutas discounts
Route::get('getDiscounts', [DiscountsController::class, 'index']);
Route::get('getDiscountsById/{id}', [DiscountsController::class, 'getById']);
Route::get('getDiscountsByPlace/{place_id}', [DiscountsController::class, 'getByPlace']);
Route::get('getDiscountsByCategory/{category_id}', [DiscountsController::class, 'getByCategory']);
Route::get('getDiscountsByDay/{day}', [DiscountsController::class, 'getByDay']);
Route::post('createDiscount', [DiscountsController::class, 'createDiscount']);
Route::post('createDiscountSchedule', [DiscountsController::class, 'createDiscountSchedule']);
Route::get('getDiscountsNow', [DiscountsController::class, 'getNow']);
    
// rutas places
Route::get('getPlaces', [placesController::class, 'index']);
Route::get('getPlacesById/{id}', [placesController::class, 'getById']);
Route::get('getPlacesOpenNow', [placesController::class, 'getOpenNow']);
Route::get('getPlacesByDay/{day}', [placesController::class, 'getByDay']);
    
// rutas products
Route::get('getProducts', [ProductsController::class, 'index']);
Route::get('getProductsById/{id}', [ProductsController::class, 'getById']);
Route::get('getProductsByCategory/{category_id}', [ProductsController::class, 'getByCategory']);

// rutas places products
Route::get('getPlacesProducts', [ProductsController::class, 'getPlacesProducts']);
Route::get('getPlacesProductsById/{id}', [ProductsController::class, 'getPlacesProductsById']);
Route::get('getPlacesProductsByPlace/{place_id}', [ProductsController::class, 'getPlacesProductsByPlace']);

// rutas de routes
Route::post('calculate-route', [RoutesController::class, 'getRoute']);