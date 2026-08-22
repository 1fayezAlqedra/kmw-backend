<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_view_all_products_with_relations(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data' => ['*' => ['id', 'name_en', 'category', 'images']]]);
    }

    public function test_admin_can_create_product_with_images(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/products', [
            'category_id' => $category->id,
            'name_en' => 'New Laptop',
            'name_ar' => 'لابتوب جديد',
            'description_en' => 'High performance',
            'description_ar' => 'أداء عالي',
            'images' => [$file],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['name_en' => 'New Laptop']);
    }

    public function test_admin_can_soft_delete_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_admin_can_delete_single_product_image(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $image = ProductImage::create(['product_id' => $product->id, 'image_path' => 'test.jpg']);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/product-images/{$image->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
    }
}
