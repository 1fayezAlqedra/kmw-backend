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
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider or ApplicationBuilder.
|
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



});
