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
| KMW Enterprise API Routes - Version 1 (V1)
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Main API V1 Architecture Wrapper
Route::prefix('v1')->group(function () {

    // ======================================================================
    // 1. Public Endpoints (Accessible by Website Visitors & External Clients)
    // ======================================================================

    // Read-only catalog resources for public frontend consumption
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);

    // Public system configurations and settings retrieval
    Route::get('settings', [SettingController::class, 'index']);

    // Public contact form submission endpoint
    Route::post('contact', [ContactController::class, 'store']);

    // ======================================================================
    // 2. Authentication Gateway
    // ======================================================================

    // Admin credential verification & Sanction Bearer Token issuance
    Route::post('auth/login', [AuthController::class, 'login']);

    // ======================================================================
    // 3. Protected Dashboard Routes (Requires Sanctum Bearer Token)
    // ======================================================================
    Route::middleware('auth:sanctum')->group(function () {

        // ------------------------------------------------------------------
        // Auth Session & Profile Management
        // ------------------------------------------------------------------
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // ------------------------------------------------------------------
        // Category Management
        // ------------------------------------------------------------------
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Product Management (Service-Layer Architecture)
        // ------------------------------------------------------------------
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);

        // Isolated media management for product galleries
        Route::delete('product-images/{id}', [ProductController::class, 'deleteImage']);

        // ------------------------------------------------------------------
        // Project Management
        // ------------------------------------------------------------------
        Route::post('projects', [ProjectController::class, 'store']);
        Route::put('projects/{id}', [ProjectController::class, 'update']);
        Route::delete('projects/{id}', [ProjectController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Video Gallery Management
        // Encapsulates full CRUD Operations via standard ApiResource
        // ------------------------------------------------------------------
        Route::apiResource('videos', VideoController::class);

        // ------------------------------------------------------------------
        // System Settings Management
        // ------------------------------------------------------------------
        Route::put('settings/{id}', [SettingController::class, 'update']);

        // ------------------------------------------------------------------
        // Contact & Inquiries Dashboard Management
        // ------------------------------------------------------------------
        Route::get('contact', [ContactController::class, 'index']);
        Route::patch('contact/{id}/toggle-read', [ContactController::class, 'toggleRead']);
        Route::delete('contact/{id}', [ContactController::class, 'destroy']);

    });

});
