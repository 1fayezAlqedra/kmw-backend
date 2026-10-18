<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_category_id' => ['required', 'integer', 'exists:project_categories,id'],

            // الاعتماد على name_ar و name_en المطابقة لما يرسله الفرونت إند وما يتوقعه الـ Service
            'name_ar'             => ['required', 'string', 'max:255'],
            'name_en'             => ['required', 'string', 'max:255'],

            'description_ar'      => ['nullable', 'string'],
            'description_en'      => ['nullable', 'string'],
            'video_url'           => ['nullable', 'url'],

            'image'               => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'images'              => ['nullable', 'array'],
            'images.*'            => ['file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_category_id.required' => 'يرجى اختيار تصنيف المشروع من القائمة.',
            'project_category_id.integer'  => 'معرف التصنيف يجب أن يكون رقماً صحيحاً.',
            'project_category_id.exists'   => 'تصنيف المشروع المختار غير موجود في النظام.',
            'name_ar.required'             => 'اسم المشروع بالعربية مطلوب.',
            'name_en.required'             => 'اسم المشروع بالإنجليزية مطلوب.',
            'video_url.url'                => 'يجب إدخال رابط فيديو صحيح.',
            'images.array'                 => 'صيغة مصفوفة الصور غير صحيحة.',
            'images.*.file'                => 'يجب أن تكون جميع الملفات المرفقة عبارة عن ملفات صالحة.',
            'images.*.mimes'               => 'صيغ الصور المسموحة هي: jpeg, png, jpg, webp, gif.',
            'images.*.max'                 => 'حجم الصورة الواحدة يجب ألا يتجاوز 5 ميجابايت.',
        ];
    }
}