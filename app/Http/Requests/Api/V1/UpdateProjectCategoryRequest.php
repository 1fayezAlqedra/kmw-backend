<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // جلب الـ Route parameter بجميع المسميات المحتملة
        $param = $this->route('project_category')
              ?? $this->route('projectCategory')
              ?? $this->route('category')
              ?? $this->route('id');

        // جلب الـ ID بمرونة سواء كان الـ Parameter عبارة عن Model Instance أو رقم ID مباشر
        $categoryId = is_object($param) ? $param->id : $param;

        return [
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('project_categories', 'name_en')->ignore($categoryId),
            ],
            'name_ar' => [
                'required',
                'string',
                'max:255',
                Rule::unique('project_categories', 'name_ar')->ignore($categoryId),
            ],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
        ];
    }
}
