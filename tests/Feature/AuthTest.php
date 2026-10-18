<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    }

    public function test_super_admin_can_create_new_admin(): void
    {
        $response = $this->actingAs($this->superAdmin, 'sanctum')->postJson('/api/v1/admins', [
            'name' => 'New Admin',
            'email' => 'newadmin@kmw.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'newadmin@kmw.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@kmw.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@kmw.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
    }
}
