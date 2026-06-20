<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * جلب جميع إعدادات الموقع العامة
     */
    public function index(): JsonResponse
    {
        // جلب الإعدادات (id, key, value, type)
        $settings = Setting::all();

        return response()->json([
            'success' => true,
            'data'    => $settings
        ], 200);
    }
}
