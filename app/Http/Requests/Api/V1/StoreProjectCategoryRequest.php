<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar'        => 'required|string|max:255|unique:project_categories,name_ar',
            'name_en'        => 'required|string|max:255|unique:project_categories,name_en',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    /**
     * تجهيز البيانات قبل بدء الـ Validation لضمان عدم تمرير قيم نصية خاطئة للصور
     */
    protected function prepareForValidation(): void
    {
        if ($this->image === 'null' || $this->image === 'undefined') {
            $this->merge([
                'image' => null,
            ]);
        }
    }
}
