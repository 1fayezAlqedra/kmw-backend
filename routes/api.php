<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\ProjectCategoryController;
use App\Http\Controllers\Api\V1\VideoController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\AdminUserController;
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
    Route::apiResource('project-categories', ProjectCategoryController::class)->only(['index', 'show']);

    // Public system configurations and settings retrieval
    Route::get('settings', [SettingController::class, 'index']);

    // Public contact form submission endpoint
    Route::post('contact', [ContactController::class, 'store']);

    // ======================================================================
    // 2. Authentication Gateway
    // ======================================================================

    // Admin credential verification & Sanctum Bearer Token issuance
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
        Route::post('categories/{id}', [CategoryController::class, 'update']); // For FormData spoofing
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Product Management (Service-Layer Architecture)
        // ------------------------------------------------------------------
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::post('products/{id}', [ProductController::class, 'update']); // For FormData spoofing
        Route::delete('products/{id}', [ProductController::class, 'destroy']);

        // Isolated media management for product galleries
        Route::delete('product-images/{id}', [ProductController::class, 'deleteImage']);

        // ------------------------------------------------------------------
        // Project Management
        // ------------------------------------------------------------------
        Route::post('projects', [ProjectController::class, 'store']);
        Route::put('projects/{id}', [ProjectController::class, 'update']);
        Route::post('projects/{id}', [ProjectController::class, 'update']); // For FormData spoofing
        Route::delete('projects/{id}', [ProjectController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Project Category Management (Service-Layer Architecture)
        // Supports Multilingual Schema (AR/EN) and Media Handling
        // ------------------------------------------------------------------
        Route::post('project-categories', [ProjectCategoryController::class, 'store']);
        Route::put('project-categories/{id}', [ProjectCategoryController::class, 'update']);
        Route::post('project-categories/{id}', [ProjectCategoryController::class, 'update']);
        Route::delete('project-categories/{id}', [ProjectCategoryController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Video Gallery Management
        // Encapsulates full CRUD Operations via standard ApiResource
        // ------------------------------------------------------------------
        Route::apiResource('videos', VideoController::class);

        // ------------------------------------------------------------------
        // Contact & Inquiries Dashboard Management
        // ------------------------------------------------------------------
        Route::get('contact', [ContactController::class, 'index']);
        Route::patch('contact/{id}/toggle-read', [ContactController::class, 'toggleRead']);
        Route::delete('contact/{id}', [ContactController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Restricted Operations (Super Admin Only)
        // ------------------------------------------------------------------
        Route::middleware('role:super_admin')->group(function () {
            // Batch update for system configurations
            Route::post('settings', [SettingController::class, 'update']);

            // Full Admin Accounts Management
            Route::apiResource('admins', AdminUserController::class)->except(['show']);
        });

    });

});
