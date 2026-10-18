<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // الحماية تتم بالفعل عبر الـ Middleware في الراوت
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
