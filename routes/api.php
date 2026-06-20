<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| KMW API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. Public Routes (المسارات العامة لموقع الزوار)
// ==========================================
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);
Route::apiResource('videos', VideoController::class)->only(['index']);
Route::get('settings', [SettingController::class, 'index']);
Route::post('contact', [ContactController::class, 'store']);

// ==========================================
// 2. Authentication Route (تسجيل دخول الأدمن)
// ==========================================
Route::post('auth/login', [AuthController::class, 'login']);

// ==========================================
// 3. Protected Dashboard Routes (مسارات لوحة التحكم المحمية)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // مسارات فحص وتأمين حساب الأدمن الحالي
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // مسارات الـ CRUD المحمية الخاصة بالـ Categories
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    // مسارات الـ CRUD المحمية الخاصة بالـ Products
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    // مسارات الـ CRUD المحمية الخاصة بالـ Projects
    Route::post('projects', [ProjectController::class, 'store']);
    Route::put('projects/{id}', [ProjectController::class, 'update']);
    Route::delete('projects/{id}', [ProjectController::class, 'destroy']);

    // ⬇️ مسارات الـ CRUD المحمية الخاصة بالـ Videos ⬇️
    Route::post('videos', [VideoController::class, 'store']);
    Route::put('videos/{id}', [VideoController::class, 'update']);
    Route::delete('videos/{id}', [VideoController::class, 'destroy']);

    // ⬇️ مسار تحديث الإعدادات المحمي ⬇️
    Route::put('settings/{id}', [SettingController::class, 'update']);

});
