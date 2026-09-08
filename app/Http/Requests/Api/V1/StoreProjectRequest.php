<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_ar'        => ['required', 'string', 'max:255'],
            'name_en'        => ['required', 'string', 'max:255'],
            'description_ar' => ['required', 'string'],
            'description_en' => ['required', 'string'],

            // الربط مع جدول تصنيفات المشاريع الجديد
            'category_id'    => ['nullable', 'exists:project_categories,id'],
            'video_url'      => ['nullable', 'url'],

            // التحقق من رفع مصفوفة الصور بشكل آمن
            'images'         => ['nullable', 'array'],
            'images.*'       => ['file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ];
    }

    /**
     * Custom messages for validation errors (Optional).
     */
    public function messages(): array
    {
        return [
            'name_ar.required'        => 'اسم المشروع بالعربية مطلوب.',
            'name_en.required'        => 'اسم المشروع بالإنجليزية مطلوب.',
            'description_ar.required' => 'الوصف بالعربية مطلوب.',
            'description_en.required' => 'الوصف بالإنجليزية مطلوب.',
            'category_id.exists'      => 'تصنيف المشروع المختار غير موجود في النظام.',
            'video_url.url'           => 'يجب إدخال رابط فيديو صحيح.',
            'images.array'            => 'صيغة مصفوفة الصور غير صحيحة.',
            'images.*.file'           => 'يجب أن تكون جميع الملفات المرفقة عبارة عن ملفات صالحة.',
            'images.*.mimes'          => 'صيغ الصور المسموحة هي: jpeg, png, jpg, webp, gif.',
            'images.*.max'            => 'حجم الصورة الواحدة يجب ألا يتجاوز 5 ميجابايت.',
        ];
    }
}
