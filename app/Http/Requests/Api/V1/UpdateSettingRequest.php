<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'site_name' => 'nullable|string|max:255',
            'email'     => 'nullable|email',
            'phone'     => 'nullable|string|max:20',
            // يمكنك إضافة أي إعدادات أخرى هنا
        ];
    }
}
