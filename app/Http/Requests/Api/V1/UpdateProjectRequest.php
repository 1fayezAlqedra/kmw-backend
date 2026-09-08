<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            // التعديل على جدول تصنيفات المشاريع الجديد
            'category_id'      => 'sometimes|nullable|exists:project_categories,id',
            'name_en'          => 'sometimes|required|string|max:255',
            'name_ar'          => 'sometimes|required|string|max:255',
            'description_en'   => 'sometimes|required|string',
            'description_ar'   => 'sometimes|required|string',
            'video_url'        => 'nullable|url',

            // رفع صور جديدة (باستخدام file لتفادي مشاكل FormData)
            'images'           => 'nullable|array',
            'images.*'         => 'file|mimes:jpeg,png,jpg,webp,gif|max:5120',

            // مصفوفة الصور المراد حذفها
            'deleted_images'   => 'nullable',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'category_id.exists' => 'تصنيف المشروع المختار غير موجود.',
            'video_url.url'      => 'رابط الفيديو غير صحيح.',
            'images.*.file'      => 'الملفات المرفقة يجب أن تكون ملفات صالحة.',
            'images.*.mimes'     => 'صيغ الصور المسموحة هي: jpeg, png, jpg, webp, gif.',
            'images.*.max'       => 'حجم الصورة الواحدة يجب ألا يتجاوز 5 ميجابايت.',
        ];
    }
}
