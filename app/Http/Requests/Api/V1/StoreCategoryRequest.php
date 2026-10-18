<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name_en.required' => 'The English name is mandatory.',
            'name_ar.required' => 'The Arabic name is mandatory.',
            'image_path.image' => 'The uploaded file must be a valid image file.',
            'image_path.mimes' => 'Allowed image formats: jpeg, png, jpg, webp.',
            'image_path.max' => 'The image size must not exceed 5MB.',
        ];
    }
}
