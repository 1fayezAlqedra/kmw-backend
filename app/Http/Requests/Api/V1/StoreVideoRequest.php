<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'required|string|max:255',
            'video_url'      => 'required|url',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ];
    }
}
