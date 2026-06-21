<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // تفعيل المرور
    }

    public function rules(): array
    {
        return [
            'title_en'    => 'required|string|max:255',
            'title_ar'    => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'client_name'    => 'nullable|string|max:255',
            'location'       => 'nullable|string|max:255',
            'images'         => 'nullable|array',
        ];
    }
}
