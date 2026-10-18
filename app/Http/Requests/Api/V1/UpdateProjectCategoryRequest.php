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
        $param = $this->route('project_category')
              ?? $this->route('projectCategory')
              ?? $this->route('category')
              ?? $this->route('id');

        $categoryId = is_object($param) ? $param->id : $param;

        return [
            'name_en' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('project_categories', 'name_en')->ignore($categoryId),
            ],
            'name_ar' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('project_categories', 'name_ar')->ignore($categoryId),
            ],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('project_categories', 'slug')->ignore($categoryId),
            ],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
        ];
    }
}
