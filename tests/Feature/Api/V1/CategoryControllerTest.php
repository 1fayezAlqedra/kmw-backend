<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء مستخدم بصلاحية أدمن لتمرير عمليات الـ CMS الحساسة
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /**
     * 1. اختبار جلب جميع التصنيفات بنجاح (Public Index)
     */
    public function test_can_fetch_all_categories()
    {
        // إدخال تصنيفين تجريبيين في قاعدة البيانات
        DB::table('categories')->insert([
            [
                'name_en'    => 'Marble Section',
                'name_ar'    => 'قسم الرخام',
                'slug'       => 'marble-section',
                'image_path' => 'categories/marble.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_en'    => 'Granite Section',
                'name_ar'    => 'قسم الجرانيت',
                'slug'       => 'granite-section',
                'image_path' => 'categories/granite.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => ['id', 'name_en', 'name_ar', 'slug', 'image_path']
                     ]
                 ]);
    }

    /**
     * 2. اختبار قيام الأدمن بإنشاء تصنيف جديد بنجاح (Store)
     * والتحقق من أن الـ Service تقوم بتوليد الـ Slug تلقائياً
     */
    public function test_admin_can_successfully_create_category()
    {
        $payload = [
            'name_en'        => 'Travertine Marble',
            'name_ar'        => 'رخام ترافرتين',
            'description_en' => 'Beautiful Italian stone',
            'description_ar' => 'حجر إيطالي جميل',
            'image_path'     => 'categories/travertine.jpg', // متوافق مع الـ Migration
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/v1/categories', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Category created successfully.'
                 ]);

        // التأكد من التخزين في قاعدة البيانات ومن عمل الـ Service في توليد الـ Slug
        $this->assertDatabaseHas('categories', [
            'name_en' => 'Travertine Marble',
            'slug'    => 'travertine-marble' // Str::slug('Travertine Marble')
        ]);
    }

    /**
     * 3. اختبار تفعيل شروط التحقق (Validation Enforcement) عند الإضافة
     */
    public function test_create_category_validation_rules()
    {
        $payload = [
            'name_en' => '', // حقل فارغ يسبب فشل التحقق
            'name_ar' => '',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/v1/categories', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name_en', 'name_ar']);
    }

    /**
     * 4. اختبار جلب تفاصيل تصنيف واحد عبر الـ Slug (Show)
     */
    public function test_can_show_category_by_slug()
    {
        $slug = 'ceramic-section';

        DB::table('categories')->insert([
            'name_en'    => 'Ceramic Section',
            'name_ar'    => 'قسم السيراميك',
            'slug'       => $slug,
            'image_path' => 'categories/ceramic.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson("/api/v1/categories/{$slug}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => [
                         'slug' => $slug
                     ]
                 ]);
    }

    /**
     * 5. اختبار قيام الأدمن بتحديث تصنيف موجود (Update)
     * والتحقق من إعادة توليد الـ Slug الجديد
     */
    public function test_admin_can_update_category()
    {
        $categoryId = DB::table('categories')->insertGetId([
            'name_en'    => 'Old Section',
            'name_ar'    => 'قسم قديم',
            'slug'       => 'old-section',
            'image_path' => 'categories/old.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updatePayload = [
            'name_en'    => 'New Luxury Marble',
            'name_ar'    => 'رخام فاخر جديد',
            'image_path' => 'categories/new.jpg',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->putJson("/api/v1/categories/{$categoryId}", $updatePayload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Category updated successfully.'
                 ]);

        // التأكد من تحديث البيانات وتعديل الـ Slug تلقائياً بناءً على الاسم الجديد
        $this->assertDatabaseHas('categories', [
            'id'      => $categoryId,
            'name_en' => 'New Luxury Marble',
            'slug'    => 'new-luxury-marble'
        ]);
    }

    /**
     * 6. اختبار قيام الأدمن بحذف تصنيف بنجاح (Destroy)
     */
    public function test_admin_can_delete_category()
    {
        $categoryId = DB::table('categories')->insertGetId([
            'name_en'    => 'Section To Delete',
            'name_ar'    => 'قسم للحذف',
            'slug'       => 'section-to-delete',
            'image_path' => 'categories/delete.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->deleteJson("/api/v1/categories/{$categoryId}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Category deleted successfully.'
                 ]);

        // التأكد من اختفاء السجل تماماً من قاعدة البيانات
        $this->assertDatabaseMissing('categories', ['id' => $categoryId]);
    }
}
