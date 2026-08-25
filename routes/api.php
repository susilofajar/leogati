<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\V1\PcBuilderApiController;
use App\Http\Controllers\Api\V1\WarrantyApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RESTful API Version 1 Routes (/api/v1/...)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // --- Autentikasi Publik API ---
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // --- Katalog, Kategori & Merek Publik ---
    Route::get('/products', [CatalogController::class, 'products']);
    Route::get('/products/{slug}', [CatalogController::class, 'productShow']);
    Route::get('/categories', [CatalogController::class, 'categories']);
    Route::get('/brands', [CatalogController::class, 'brands']);

    // --- Utilitas Publik (Garansi & PC Builder) ---
    Route::get('/warranty/check', [WarrantyApiController::class, 'check']);
    Route::post('/pc-builder/validate', [PcBuilderApiController::class, 'validate']);

    // --- Endpoint Terproteksi (Bearer Token Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Pesanan Pelanggan
        Route::get('/orders', [OrderApiController::class, 'index']);
        Route::get('/orders/{order_number}', [OrderApiController::class, 'show']);
    });
});
