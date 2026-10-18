<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. اختبار تسجيل الدخول بنجاح وتوليد التوكن
     */
    public function test_user_can_login_with_correct_credentials()
    {
        // إنشاء مستخدم بكلمة مرور معروفة
        $user = User::factory()->create([
            'email'    => 'fayez@kmw.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        $payload = [
            'email'    => 'fayez@kmw.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/login', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'access_token',
                     'user' => ['id', 'email', 'role']
                 ])
                 ->assertJson(['success' => true]);
    }

    /**
     * 2. اختبار رفض تسجيل الدخول ببيانات خاطئة
     */
    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email'    => 'fayez@kmw.com',
            'password' => Hash::make('password123'),
        ]);

        $payload = [
            'email'    => 'fayez@kmw.com',
            'password' => 'wrong-password', // كلمة مرور خاطئة عمداً
        ];

        $response = $this->postJson('/api/v1/auth/login', $payload);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Invalid credentials.'
                 ]);
    }

    /**
     * 3. اختبار جلب بيانات المستخدم المسجل حالياً (Me Route)
     */
    public function test_authenticated_user_can_fetch_their_profile_data()
    {
        $user = User::factory()->create(['role' => 'admin']);

        // actingAs مع 'sanctum' تحاكي إرسال الـ Bearer Token في الـ Headers
        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => [
                         'id'    => $user->id,
                         'email' => $user->email
                     ]
                 ]);
    }

    /**
     * 4. اختبار حظر الوصول لبيانات البروفايل إذا كان المستخدم غير مسجل دخول
     */
    public function test_unauthenticated_user_cannot_access_profile_data()
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    /**
     * 5. اختبار تسجيل الخروج وتدمير التوكن بنجاح
     */
    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Logged out.'
                 ]);
    }
}
