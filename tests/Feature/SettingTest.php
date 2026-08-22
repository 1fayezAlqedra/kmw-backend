<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // ننشئ مستخدم بصلاحية super_admin ليجتاز CheckRole بنجاح
        $this->admin = User::factory()->create(['role' => 'super_admin']);
    }

    public function test_can_view_settings(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'KMW CMS']);

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/settings');

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data'])
                 ->assertJsonFragment(['site_name' => 'KMW CMS']);
    }

public function test_super_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/settings', [
            'site_name' => 'Updated KMW Platform',
            'email' => 'info@kmw.com',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('settings', [
            'key' => 'site_name',
            'value' => 'Updated KMW Platform',
        ]);
    }
}