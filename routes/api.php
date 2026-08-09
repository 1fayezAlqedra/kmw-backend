<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\VideoController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\AuthController;

/*
|--------------------------------------------------------------------------
| KMW API Routes - Enterprise Versioning Architecture (V1)
|--------------------------------------------------------------------------
*/

// Encapsulating all API routes under the 'v1' prefix for future API versioning management
Route::prefix('v1')->group(function () {

    // ======================================================================
    // 1. Public Routes (Accessible by web visitors and external clients)
    // ======================================================================
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);

    // REMOVED: Route::apiResource('videos', VideoController::class)->only(['index']);
    // (تم حذفه من هنا لمنع Duplicate Route Entry مع المسارات المحمية بالأسفل)

    Route::get('settings', [SettingController::class, 'index']);
    Route::post('contact', [ContactController::class, 'store']);

    // ======================================================================
    // 2. Authentication Gateway (Admin login and token issuance)
    // ======================================================================
    Route::post('auth/login', [AuthController::class, 'login']);

    // ======================================================================
    // 3. Protected Dashboard Routes (Requires valid Sanctum Bearer Token)
    // ======================================================================
    Route::middleware('auth:sanctum')->group(function () {

        // Session and account management
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Protected Category management routes
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // Protected Product management routes (Service Layer Pattern)
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);

        // Single Product Image deletion route
        Route::delete('product-images/{id}', [ProductController::class, 'deleteImage']);

        // Protected Project management routes
        Route::post('projects', [ProjectController::class, 'store']);
        Route::put('projects/{id}', [ProjectController::class, 'update']);
        Route::delete('projects/{id}', [ProjectController::class, 'destroy']);

        // Protected Video gallery management routes
        // REMOVED: Route::post('videos', [VideoController::class, 'store']);
        // REMOVED: Route::put('videos/{id}', [VideoController::class, 'update']);
        // REMOVED: Route::delete('videos/{id}', [VideoController::class, 'destroy']);
        // REMOVED: Route::get('/videos', [VideoController::class, 'index']);
        // (تم استبدال المسارات الأربعة المفرقة بـ apiResource شامل وواضح لتفادي أي Route Collision)
        Route::apiResource('videos', VideoController::class);

        // Protected System Settings routes
        Route::put('settings/{id}', [SettingController::class, 'update']);

        // Contact inquiries management in dashboard
        Route::get('contact', [ContactController::class, 'index']);
        Route::delete('contact/{id}', [ContactController::class, 'destroy']);

    });

});
