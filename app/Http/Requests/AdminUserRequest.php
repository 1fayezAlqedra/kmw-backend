<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'super_admin';
    }

    public function rules(): array
    {
        $adminId = $this->route('admin') ? $this->route('admin')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($adminId)],
            'password' => $adminId ? 'nullable|string|min:8' : 'required|string|min:8',
            'role' => 'required|in:admin,super_admin',
        ];
    }
}
