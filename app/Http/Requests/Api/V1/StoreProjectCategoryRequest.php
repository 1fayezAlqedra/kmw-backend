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
            'name_ar'        => 'required|string|max:255',
            'name_en'        => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:project_categories,slug',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required' => 'اسم القسم بالعربي مطلوب.',
            'name_en.required' => 'اسم القسم بالإنجليزي مطلوب.',
            'slug.unique'      => 'الـ slug مستخدم من قبل.',
            'image.image'      => 'الملف المرفق يجب أن يكون صورة.',
            'image.mimes'      => 'صيغ الصور المسموحة هي: jpeg, png, jpg, webp, gif.',
            'image.max'        => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت.',
        ];
    }
}
