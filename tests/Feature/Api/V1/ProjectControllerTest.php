<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /**
     * 1. اختبار جلب كل المشاريع
     */
    public function test_can_fetch_all_projects()
    {
        Project::factory()->count(2)->create();

        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    /**
     * 2. اختبار إنشاء مشروع بنجاح بواسطة الأدمن
     */
    public function test_admin_can_successfully_create_project_with_images()
    {
        $payload = [
            'name_en'        => 'Luxury Marble Palace', // تأكدنا تماماً أنها name_en وليست title_en
            'name_ar'        => 'قصر الرخام الفاخر',
            'description_en' => 'A grand palace project using premium Calacatta marble.',
            'description_ar' => 'مشروع قصر ضخم باستخدام رخام كالاكاتا الممتاز.',
            'images'         => [
                'projects/palace-1.jpg',
                'projects/palace-2.jpg'
            ]
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/v1/projects', $payload);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('projects', [
            'name_en' => 'Luxury Marble Palace'
        ]);

        $project = Project::where('name_en', 'Luxury Marble Palace')->first();

        // الفحص يتوافق مع جدول project_images وحقل image_path
        $this->assertDatabaseHas('project_images', [
            'project_id' => $project->id,
            'image_path' => 'projects/palace-1.jpg'
        ]);
    }

    /**
     * 3. اختبار منع الزوار
     */
    public function test_guest_cannot_create_project()
    {
        $payload = [
            'name_en' => 'Unauthorized Project',
            'name_ar' => 'مشروع غير مصرح به',
        ];

        $response = $this->postJson('/api/v1/projects', $payload);

        $response->assertStatus(401);
    }
}
