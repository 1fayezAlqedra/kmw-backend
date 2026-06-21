<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateSettingRequest;
use App\Services\V1\SettingService;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(): JsonResponse
    {
        $settings = Setting::first(); // نفترض أن الإعدادات في صف واحد
        return response()->json(['success' => true, 'data' => $settings], 200);
    }

    public function update(UpdateSettingRequest $request, string $id): JsonResponse
    {
        $setting = Setting::findOrFail($id);
        $updated = $this->settingService->updateSetting($setting, $request->validated());

        return response()->json(['success' => true, 'data' => $updated], 200);
    }
}
