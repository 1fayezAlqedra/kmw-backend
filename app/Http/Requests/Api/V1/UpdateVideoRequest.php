<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
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
            'title_en'       => 'sometimes|required|string|max:255',
            'title_ar'       => 'sometimes|required|string|max:255',
            'video_url'      => 'sometimes|required|url',
            'description_en' => 'sometimes|required|string',
            'description_ar' => 'sometimes|required|string',
        ];
    }
}
