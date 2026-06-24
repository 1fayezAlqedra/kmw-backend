<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityBoundaryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. اختبار حظر الضيوف من الوصول للعمليات الحساسة
     */
    public function test_unauthenticated_requests_to_create_products_are_strictly_blocked()
    {
        $response = $this->postJson('/api/v1/products', []);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.'
                 ]);
    }

    /**
     * 2. اختبار صلاحيات المدير في إضافة منتج جديد
     */
    public function test_authorized_admin_can_successfully_create_a_new_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $categoryId = DB::table('categories')->insertGetId([
            'name_ar'    => 'قسم الرخام',
            'name_en'    => 'Marble Section',
            'slug'       => 'marble-section-admin',
            'image_path' => 'categories/test.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'category_id'    => $categoryId,
            'name_ar'        => 'رخام كالاكاتا فاخر',
            'name_en'        => 'Premium Calacatta Marble',
            'slug'           => 'premium-calacatta-marble',
            'description_ar' => 'وصف المنتج باللغة العربية',
            'description_en' => 'Product description in English',
        ];

        $response = $this->actingAs($admin, 'sanctum')
                         ->postJson('/api/v1/products', $payload);

        $response->assertStatus(201);
    }

    /**
     * 3. اختبار أن الـ Editor يمتلك الصلاحية للحذف بنجاح
     */
    public function test_authorized_editor_can_successfully_delete_data_assets()
    {
        $editor = User::factory()->create(['role' => 'editor']);

        $categoryId = DB::table('categories')->insertGetId([
            'name_ar'    => 'قسم الجرانيت',
            'name_en'    => 'Granite Section',
            'slug'       => 'granite-section-editor',
            'image_path' => 'categories/test.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $productId = DB::table('products')->insertGetId([
            'category_id'    => $categoryId,
            'name_ar'        => 'رخام تجريبي',
            'name_en'        => 'Test Marble',
            'slug'           => 'test-marble-slug',
            'description_ar' => 'وصف تجريبي',
            'description_en' => 'Test description',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $response = $this->actingAs($editor, 'sanctum')
                         ->deleteJson("/api/v1/products/{$productId}");

        $this->assertTrue($response->isSuccessful(), "Expected successful status code, got {$response->status()}");
    }
}
