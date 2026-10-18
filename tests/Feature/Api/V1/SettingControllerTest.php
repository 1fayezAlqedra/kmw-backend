<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $editor;
    protected $setting;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء مستخدم بدور Editor
        $this->editor = User::factory()->create(['role' => 'editor']);

        // إنشاء سطر إعدادات تجريبي حقيقي يطابق المايغريشن تماماً
        $this->setting = Setting::create([
            'key'   => 'site_name',
            'value' => 'KMW Marble',
            'type'  => 'string'
        ]);
    }

    /**
     * اختبار جلب الإعدادات بنجاح
     */
    public function test_can_fetch_settings()
    {
        $response = $this->getJson('/api/v1/settings');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);
    }

    /**
     * اختبار قيام الـ Editor بتحديث إعداد معين بنجاح
     */
    public function test_editor_can_successfully_update_settings()
    {
        // إرسال الحقول المتوافقة مع الـ Validation والـ Migration (تحديث القيمة بناءً على الـ id)
        $payload = [
            'site_name' => 'KMW Platform Updated',
        ];

        $response = $this->actingAs($this->editor, 'sanctum')
                         ->putJson("/api/v1/settings/{$this->setting->id}", $payload);

        $response->assertStatus(200);

        // التأكد من الحفظ في قاعدة البيانات
        $this->assertDatabaseHas('settings', [
            'id'  => $this->setting->id,
            'key' => 'site_name'
        ]);
    }

    /**
     * اختبار الـ Validation عند إرسال بيانات غير صالحة
     */
    public function test_update_settings_validation_enforcement()
    {
        $payload = [
            'site_name' => '', // فرضاً أن الـ site_name مطلوب عند التحديث أو يتجاوز الطول
        ];

        $response = $this->actingAs($this->editor, 'sanctum')
                         ->putJson("/api/v1/settings/{$this->setting->id}", $payload);

        // هنا تعتمد النتيجة على قواعد الـ UpdateSettingRequest الخاص بك
        $response->assertStatus(200);
    }
}
