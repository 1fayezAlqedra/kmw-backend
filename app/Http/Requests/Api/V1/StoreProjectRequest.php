<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // تفعيل المرور
    }

    // app/Http/Requests/Api/V1/StoreProjectRequest.php
    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'video_url' => 'nullable|url',
            'images' => 'nullable|array',
        ];
    }
}
