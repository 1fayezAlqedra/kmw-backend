<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم يملك صلاحية لعمل هذا الطلب.
     * هنا نضع 'true' للسماح للمسؤولين بالوصول (يتم التحكم في الصلاحيات لاحقاً عبر الـ Middleware).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * الحصول على قواعد التحقق التي تنطبق على الطلب.
     * هنا نضع كافة الشروط التي يجب أن تستوفيها بيانات التصنيف.
     */
    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image_path' => 'nullable|string', // حالياً نستقبل رابط الصورة كنص
        ];
    }

    /**
     * (اختياري) تخصيص رسائل الخطأ لتظهر بشكل مفهوم للـ Frontend.
     */
    public function messages(): array
    {
        return [
            'name_en.required' => 'The English name is mandatory.',
            'name_ar.required' => 'الاسم باللغة العربية مطلوب.',
        ];
    }
}
