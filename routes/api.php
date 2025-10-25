<?php

use App\Http\Controllers\DiscountsController;
use App\Http\Controllers\placesController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JWTAuthController;


Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('user', [JWTAuthController::class, 'me']);
    Route::post('logout', [JWTAuthController::class, 'logout']);
    // rutas 
    Route::post('verify-token', [JWTAuthController::class, 'verifyToken']);
});

Route::get('getDiscounts', [DiscountsController::class, 'index']);
    
// rutas places
Route::get('getPlaces', [placesController::class, 'index']);
    
// rutas products
Route::get('getProducts', [ProductsController::class, 'index']);