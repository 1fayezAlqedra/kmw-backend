<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $categoryId;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء أدمن للاختبار
        $this->admin = User::factory()->create(['role' => 'admin']);

        // إنشاء تصنيف تجريبي
        $this->categoryId = DB::table('categories')->insertGetId([
            'name_ar'    => 'قسم الرخام',
            'name_en'    => 'Marble Section',
            'slug'       => 'marble-section-test',
            'image_path' => 'categories/test.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_can_fetch_all_products()
    {
        $productId = DB::table('products')->insertGetId([
            'category_id'    => $this->categoryId,
            'name_en'        => 'Calacatta Marble',
            'name_ar'        => 'رخام كالاكاتا',
            'slug'           => 'calacatta-marble',
            'description_en' => 'High quality marble',
            'description_ar' => 'رخام عالي الجودة',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        DB::table('product_images')->insert([
            'product_id' => $productId,
            'image_path' => 'products/marble.jpg',
            'is_main'    => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    public function test_admin_can_successfully_create_product()
    {
        Storage::fake('public');

        $payload = [
            'category_id'    => $this->categoryId,
            'name_en'        => 'Greek White Marble',
            'name_ar'        => 'رخام يوناني أبيض',
            'slug'           => 'greek-white-marble',
            'description_en' => 'Premium Greek marble texture',
            'description_ar' => 'خامة رخام يوناني ممتاز',
            'images'         => [
                UploadedFile::fake()->image('marble1.jpg'),
                UploadedFile::fake()->image('marble2.jpg')
            ]
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/v1/products', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['slug' => 'greek-white-marble']);
    }

    public function test_create_product_validation_enforcement()
    {
        $payload = [
            'category_id' => $this->categoryId,
            'name_en'     => '' // حقل فارغ يسبب خطأ التحقق
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/v1/products', $payload);

        $response->assertStatus(422);
    }

    public function test_can_show_product_by_slug()
    {
        $slug = 'test-product-slug';

        DB::table('products')->insert([
            'category_id'    => $this->categoryId,
            'name_en'        => 'Test Product',
            'name_ar'        => 'منتج تجريبي',
            'description_en' => 'Desc',
            'description_ar' => 'وصف',
            'slug'           => $slug,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $response = $this->getJson("/api/v1/products/{$slug}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    public function test_admin_can_completely_delete_product_and_its_images()
    {
        $productId = DB::table('products')->insertGetId([
            'category_id'    => $this->categoryId,
            'name_en'        => 'Product to Delete',
            'name_ar'        => 'منتج للحذف',
            'slug'           => 'product-to-delete',
            'description_en' => 'Desc',
            'description_ar' => 'وصف',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        DB::table('product_images')->insert([
            'product_id' => $productId,
            'image_path' => 'products/delete-me.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->deleteJson("/api/v1/products/{$productId}");

        $response->assertStatus(200);

        // استخدام assertSoftDeleted لأنك فعلت SoftDeletes في الموديل
        $this->assertSoftDeleted('products', ['id' => $productId]);
    }
}
