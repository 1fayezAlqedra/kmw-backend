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

// تغليف كافة مسارات النظام داخل Prefix 'v1' لتأمين إصدارات الـ API المستقبلية للشركة
Route::prefix('v1')->group(function () {

    // ======================================================================
    // 1. Public Routes (المسارات العامة المفتوحة لموقع الزوار والتطبيقات الخرجية)
    // ======================================================================
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);
    Route::apiResource('videos', VideoController::class)->only(['index']);

    Route::get('settings', [SettingController::class, 'index']);
    Route::post('contact', [ContactController::class, 'store']);

    // ======================================================================
    // 2. Authentication Gateway (بوابة تسجيل دخول مسؤولين الشركة وإصدار الرموز)
    // ======================================================================
    Route::post('auth/login', [AuthController::class, 'login']);

    // ======================================================================
    // 3. Protected Dashboard Routes (لوحة التحكم المؤمنة - تشترط الـ Access Token)
    // ======================================================================
    Route::middleware('auth:sanctum')->group(function () {

        // إدارة جلسة الحساب الحالية
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // العمليات المحمية للأقسام والـ Categories
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // العمليات المحمية للمنتجات والـ Products (التي تعمل الآن بنمط الـ Service المطور)
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);

        // العمليات المحمية للمشاريع والـ Projects
        Route::post('projects', [ProjectController::class, 'store']);
        Route::put('projects/{id}', [ProjectController::class, 'update']);
        Route::delete('projects/{id}', [ProjectController::class, 'destroy']);

        // العمليات المحمية لمعرض الملتيميديا والـ Videos
        Route::post('videos', [VideoController::class, 'store']);
        Route::put('videos/{id}', [VideoController::class, 'update']);
        Route::delete('videos/{id}', [VideoController::class, 'destroy']);

        // العمليات المحمية لإعدادات المنصة وهيكلة الثوابت
        Route::put('settings/{id}', [SettingController::class, 'update']);

        // عرض وإدارة رسائل التواصل الواردة من العملاء داخل لوحة التحكم
        Route::get('contact', [ContactController::class, 'index']);
        Route::delete('contact/{id}', [ContactController::class, 'destroy']);

    });

});
