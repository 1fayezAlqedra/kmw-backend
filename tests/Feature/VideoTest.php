<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_view_all_videos(): void
    {
        Video::create([
            'title_en' => 'Test Video',
            'title_ar' => 'فيديو تجريبي',
            'youtube_url' => 'https://youtube.com/watch?v=test',
        ]);

        // إضافة actingAs للمصادقة لأن مسار العرض محمي
        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/videos');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    public function test_admin_can_create_video(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/videos', [
            'title_en' => 'Intro Video',
            'title_ar' => 'فيديو تعريفي',
            'video_url' => 'https://youtube.com/watch?v=example',
            'description_en' => 'Sample description',
            'description_ar' => 'وصف عينة',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('videos', ['title_en' => 'Intro Video']);
    }

    public function test_admin_can_update_video(): void
    {
        $video = Video::create([
            'title_en' => 'Old Title',
            'title_ar' => 'عنوان قديم',
            'youtube_url' => 'https://youtube.com/watch?v=old',
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/v1/videos/{$video->id}", [
            'title_en' => 'Updated Video Title',
            'title_ar' => 'عنوان فيديو محدث',
            'video_url' => 'https://youtube.com/watch?v=new',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('videos', ['title_en' => 'Updated Video Title']);
    }

    public function test_admin_can_delete_video(): void
    {
        $video = Video::create([
            'title_en' => 'Video to Delete',
            'title_ar' => 'فيديو للحذف',
            'youtube_url' => 'https://youtube.com/watch?v=delete',
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/videos/{$video->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }
}
