<?php

namespace App\Services\V1;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * إنشاء أدمن جديد بواسطة الـ SEO
     */
    public function createAdmin(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'admin'; // تحديد الدور تلقائياً

        return User::create($data);
    }
}
