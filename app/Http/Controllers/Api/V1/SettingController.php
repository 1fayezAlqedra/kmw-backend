<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Display a listing of the system settings.
     */
    public function index(): JsonResponse
    {
        $settings = Setting::pluck('value', 'key')->all();

        return response()->json([
            'status' => 'success',
            'data' => $settings,
        ]);
    }

    /**
     * Update the specified system settings in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'copyright_text' => 'nullable|string|max:255',
            'factory_address' => 'nullable|string',
            'showroom_address' => 'nullable|string',
            'google_maps_url' => 'nullable|string',
            'instagram' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'twitter' => 'nullable|string',
            'youtube' => 'nullable|string',
            'working_hours_weekdays' => 'nullable|string|max:255',
            'working_hours_friday' => 'nullable|string|max:255',
        ]);

        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'System settings updated successfully.',
        ]);
    }
}
