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
*/

Route::prefix('v1')->group(function () {

    // ======================================================================
    // 1. Public Endpoints (Website Visitors & External Clients)
    // ======================================================================

    // Read-only catalog resources
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);

    Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/{id}', [ProjectController::class, 'show']);

    Route::get('project-categories', [ProjectCategoryController::class, 'index']);
    Route::get('project-categories/{id}', [ProjectCategoryController::class, 'show']);

    // Public configurations & contact submission
    Route::get('settings', [SettingController::class, 'index']);
    Route::post('contact', [ContactController::class, 'store']);

    // ======================================================================
    // 2. Authentication Gateway
    // ======================================================================
    Route::post('auth/login', [AuthController::class, 'login']);

    // ======================================================================
    // 3. Protected Dashboard Routes (Sanctum Bearer Token Required)
    // ======================================================================
    Route::middleware('auth:sanctum')->group(function () {

        // Auth Session Management
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // ------------------------------------------------------------------
        // Product Categories (الأحجار والجرانيت)
        // ------------------------------------------------------------------
        Route::post('categories', [CategoryController::class, 'store']);
        Route::match(['put', 'patch'], 'categories/{id}', [CategoryController::class, 'update']);
        Route::post('categories/{id}', [CategoryController::class, 'update']); // FormData Spoofing
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Products Management
        // ------------------------------------------------------------------
        Route::post('products', [ProductController::class, 'store']);
        Route::match(['put', 'patch'], 'products/{id}', [ProductController::class, 'update']);
        Route::post('products/{id}', [ProductController::class, 'update']); // FormData Spoofing
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
        Route::delete('product-images/{id}', [ProductController::class, 'deleteImage']);

        // ------------------------------------------------------------------
        // Projects Management
        // ------------------------------------------------------------------
        Route::post('projects', [ProjectController::class, 'store']);
        Route::match(['put', 'patch'], 'projects/{id}', [ProjectController::class, 'update']);
        Route::post('projects/{id}', [ProjectController::class, 'update']); // FormData Spoofing
        Route::delete('projects/{id}', [ProjectController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Project Categories Management (أقسام المشاريع - أرضيات، درج، إلخ)
        // ------------------------------------------------------------------
        Route::post('project-categories', [ProjectCategoryController::class, 'store']);
        Route::match(['put', 'patch'], 'project-categories/{id}', [ProjectCategoryController::class, 'update']);
        Route::post('project-categories/{id}', [ProjectCategoryController::class, 'update']); // FormData Spoofing
        Route::delete('project-categories/{id}', [ProjectCategoryController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Videos Gallery
        // ------------------------------------------------------------------
        Route::apiResource('videos', VideoController::class);

        // ------------------------------------------------------------------
        // Contact Dashboard
        // ------------------------------------------------------------------
        Route::get('contact', [ContactController::class, 'index']);
        Route::patch('contact/{id}/toggle-read', [ContactController::class, 'toggleRead']);
        Route::delete('contact/{id}', [ContactController::class, 'destroy']);

        // ------------------------------------------------------------------
        // Super Admin Only Operations
        // ------------------------------------------------------------------
        Route::middleware('role:super_admin')->group(function () {
            Route::post('settings', [SettingController::class, 'update']);
            Route::apiResource('admins', AdminUserController::class)->except(['show']);
        });

    });

});
