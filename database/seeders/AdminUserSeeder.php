<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin (السوبر أدمن الرئيسي)
        User::updateOrCreate(
            ['email' => 'superadmin@kmw.com'],
            [
                'name' => 'Fayez Alqedra',
                'password' => Hash::make('password123'),
                'role' => 'super_admin', // أو 'admin' حسب المسميات عندك في الـ DB
                'email_verified_at' => now(),
            ]
        );

        // 2. Admin 1 (أدمن فرعي أول)
        User::updateOrCreate(
            ['email' => 'admin1@kmw.com'],
            [
                'name' => 'Ahmed Hassan',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 3. Admin 2 (أدمن فرعي ثاني)
        User::updateOrCreate(
            ['email' => 'admin2@kmw.com'],
            [
                'name' => 'Sara Ali',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
