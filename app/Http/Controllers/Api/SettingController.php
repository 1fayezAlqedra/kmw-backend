<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Public & Admin: جلب الإعدادات الحالية لموقع الزوار أو اللوحة
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all();
        return response()->json(['success' => true, 'data' => $settings], 200);
    }

    /**
     * Admin CMS: تحديث قيمة إعداد معين عبر الـ ID
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $setting = Setting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'value' => 'required|string', // التأكد من إرسال القيمة الجديدة
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $setting->update([
            'value' => $request->value
        ]);

        return response()->json([
            'success' => true,
            'message' => "Setting updated successfully.",
            'data'    => $setting
        ], 200);
    }
}
