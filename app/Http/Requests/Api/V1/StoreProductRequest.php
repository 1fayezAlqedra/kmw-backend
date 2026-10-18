<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم يملك صلاحية لعمل هذا الطلب.
     */
    public function authorize(): bool
    {
        // غيرها من false إلى true لتسمح للطلب بالمرور حالياً
        return true;
    }

    /**
     * هنا نضع شروط التحقق من البيانات (التي كانت سابقاً داخل الـ Controller)
     */
    public function rules(): array
    {
        return [
            'category_id'    => 'required|exists:categories,id',
            'name_en'        => 'required|string|max:255',
            'name_ar'        => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'images'         => 'nullable|array',
        ];
    }
}
