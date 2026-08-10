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
            'category_id'      => 'required|exists:categories,id',
            'name_en'          => 'sometimes|required|string|max:255',
            'name_ar'          => 'sometimes|required|string|max:255',
            'description_en'   => 'sometimes|required|string',
            'description_ar'   => 'sometimes|required|string',

            // رفع صور جديدة
            'images'           => 'nullable|array',
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

            // 🔴 التعديل الجوهري: السماح باستقبال مصفوفة الصور المراد حذفها
            'deleted_images'   => 'nullable|array',
            'deleted_images.*' => 'integer|exists:project_images,id',
        ];
    }
}
