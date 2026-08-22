<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_view_all_projects_with_relations(): void
    {
        $category = Category::factory()->create();
        Project::factory()->count(2)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    public function test_admin_can_create_project_with_images(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->image('project.jpg');

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/projects', [
            'category_id' => $category->id,
            'name_en' => 'E-Commerce Platform',
            'name_en' => 'E-Commerce Platform',
            'name_ar' => 'منصة تجارة إلكترونية',
            'description_en' => 'Full stack project description',
            'description_ar' => 'وصف مشروع متكامل',
            'images' => [$file],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', ['name_en' => 'E-Commerce Platform']);
    }

    public function test_admin_can_update_project(): void
    {
        $category = Category::factory()->create();
        $project = Project::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/v1/projects/{$project->id}", [
            'category_id' => $category->id,
            'name_en' => 'Updated Project Name',
            'name_ar' => 'اسم مشروع محدث',
            'description_en' => 'Updated description',
            'description_ar' => 'وصف محدث',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', ['name_en' => 'Updated Project Name']);
    }

    public function test_admin_can_soft_delete_project(): void
    {
        $category = Category::factory()->create();
        $project = Project::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/projects/{$project->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }
}
