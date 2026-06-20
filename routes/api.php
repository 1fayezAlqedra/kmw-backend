<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\SettingController;

/*
|--------------------------------------------------------------------------
| KMW API Routes
|--------------------------------------------------------------------------
*/

// المسارات العامة (Public Routes) - للزوار والموقع الأمامي (Vue.js Front-end)
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);
Route::apiResource('videos', VideoController::class)->only(['index']);
Route::get('settings', [SettingController::class, 'index']);

// مسار إرسال رسائل "اتصل بنا" من الموقع
Route::post('contact', [ContactController::class, 'store']);


// ملاحظة مستحبة: مسارات لوحة التحكم (Admin Dashboard) سنضعها لاحقاً
// بداخل نظام حماية (Middleware) بعد إعداد الـ Authentication والـ Tokens.
