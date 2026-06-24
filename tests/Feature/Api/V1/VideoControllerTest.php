<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $editor;
    protected $video;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء المستخدمين بالأدوار المعتمدة للنظام
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->editor     = User::factory()->create(['role' => 'editor']);

        // إنشاء فيديو تجريبي مطابق لقاعدة البيانات (المايغريشن)
        $this->video = Video::create([
            'title_en'    => 'KMW Marble Production Line',
            'title_ar'    => 'خط إنتاج رخام كي إم دبليو',
            'youtube_url' => 'https://www.youtube.com/watch?v=example',
        ]);
    }

    /**
     * اختبار جلب قائمة الفيديوهات بنجاح للـ Frontend
     */
    public function test_can_fetch_all_videos()
    {
        $response = $this->getJson('/api/v1/videos');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    /**
     * اختبار قيام الـ Editor بإضافة فيديو جديد بنجاح
     */
    public function test_editor_can_successfully_create_video()
    {
        // إرسال الحقول المطلوبة في الـ StoreVideoRequest
        $payload = [
            'title'       => 'New Marble Installation Guide',
            'url'         => 'https://www.youtube.com/watch?v=new-video',
            'description' => 'Description test',
        ];

        $response = $this->actingAs($this->editor, 'sanctum')
                         ->postJson('/api/v1/videos', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true
                 ]);

        // الفحص في قاعدة البيانات للتأكد من نجاح الـ Mapping داخل الـ Service
        $this->assertDatabaseHas('videos', [
            'youtube_url' => 'https://www.youtube.com/watch?v=new-video',
            'title_en'    => 'New Marble Installation Guide'
        ]);
    }

    /**
     * اختبار قيام الـ Editor بتحديث فيديو بنجاح
     */
    public function test_editor_can_successfully_update_video()
    {
        // إرسال الحقول المطلوبة في الـ StoreVideoRequest للتحديث
        $payload = [
            'title'       => 'Updated Video Title',
            'url'         => 'https://www.youtube.com/watch?v=updated',
            'description' => 'Updated description test',
        ];

        $response = $this->actingAs($this->editor, 'sanctum')
                         ->putJson("/api/v1/videos/{$this->video->id}", $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);

        // التأكد من تحديث الحقول في قاعدة البيانات
        $this->assertDatabaseHas('videos', [
            'id'          => $this->video->id,
            'youtube_url' => 'https://www.youtube.com/watch?v=updated',
            'title_en'    => 'Updated Video Title'
        ]);
    }

    /**
     * اختبار الحذف النهائي للفيديو
     */
    public function test_editor_or_admin_can_delete_video()
    {
        $response = $this->actingAs($this->editor, 'sanctum')
                         ->deleteJson("/api/v1/videos/{$this->video->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Video deleted.']);

        $this->assertDatabaseMissing('videos', ['id' => $this->video->id]);
    }
}
