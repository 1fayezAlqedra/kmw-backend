<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_categories_list(): void
    {
        Category::create([
            'name_en' => 'Test Tech',
            'name_ar' => 'تكنولوجيا تجريبية',
            'slug' => 'test-tech',
            'image_path' => 'categories/test.jpg'
        ]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => ['id', 'name_en', 'name_ar', 'slug', 'image_path']
                     ]
                 ]);
    }

    public function test_super_admin_can_create_category(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'super_admin']);
        $file = UploadedFile::fake()->image('category.jpg');

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/v1/categories', [
            'name_en' => 'Technology',
            'name_ar' => 'تكنولوجيا',
            'slug' => 'technology',
            'description_en' => 'Tech category',
            'description_ar' => 'فئة التكنولوجيا',
            'image_path' => $file,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Category created successfully.',
                 ]);

        $this->assertDatabaseHas('categories', ['slug' => 'technology']);
    }

    public function test_super_admin_can_delete_category(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $category = Category::create([
            'name_en' => 'Old Category',
            'name_ar' => 'تصنيف قديم',
            'slug' => 'old-category',
            'image_path' => 'categories/old.jpg'
        ]);

        $response = $this->actingAs($admin, 'sanctum')->deleteJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Category deleted successfully.',
                 ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

public function test_admin_can_create_category(): void
    {
        Storage::fake('public');

        // اختبار أن الـ admin الطبيعي مسموح له أيضاً بالإضافة
        $admin = User::factory()->create(['role' => 'admin']);
        $file = UploadedFile::fake()->image('category.jpg');

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/v1/categories', [
            'name_en' => 'Technology Admin',
            'name_ar' => 'تكنولوجيا المشرف',
            'slug' => 'technology-admin',
            'image_path' => $file,
        ]);

        $response->assertStatus(201);
    }
}
