<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // الكاتيجوري قادمة من Dropdown ويجب أن تكون موجودة في جدول project_categories
            'category_id'      => 'sometimes|required|exists:project_categories,id',
            'name_en'          => 'sometimes|required|string|max:255',
            'name_ar'          => 'sometimes|required|string|max:255',
            'slug'             => 'sometimes|nullable|string|max:255',
            'description_en'   => 'sometimes|nullable|string',
            'description_ar'   => 'sometimes|nullable|string',
            'video_url'        => 'nullable|url',

            // رفع صور جديدة
            'images'           => 'nullable|array',
            'images.*'         => 'file|mimes:jpeg,png,jpg,webp,gif|max:5120',

            // مصفوفة معرفات الصور المراد حذفها
            'deleted_images'   => 'nullable|array',
            'deleted_images.*' => 'integer|exists:project_images,id',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'  => 'يرجى اختيار تصنيف المشروع من القائمة.',
            'category_id.exists'    => 'تصنيف المشروع المختار غير موجود.',
            'video_url.url'         => 'رابط الفيديو غير صحيح.',
            'images.*.file'         => 'الملفات المرفقة يجب أن تكون ملفات صالحة.',
            'images.*.mimes'        => 'صيغ الصور المسموحة هي: jpeg, png, jpg, webp, gif.',
            'images.*.max'          => 'حجم الصورة الواحدة يجب ألا يتجاوز 5 ميجابايت.',
            'deleted_images.*.exists' => 'إحدى الصور المراد حذفها غير موجودة.',
        ];
    }
}
