<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_can_send_contact_message(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '0123456789',
            'message' => 'This is a test inquiry message.',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'john@example.com']);
    }

    public function test_admin_can_view_all_contact_messages(): void
    {
        Contact::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Another inquiry.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/contact');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    public function test_admin_can_toggle_message_read_status(): void
    {
        $contact = Contact::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'message' => 'Read status test.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->patchJson("/api/v1/contact/{$contact->id}/toggle-read");

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'is_read' => true]);

        $this->assertDatabaseHas('contact_messages', [
            'id' => $contact->id,
            'is_read' => true,
        ]);
    }

    public function test_admin_can_delete_contact_message(): void
    {
        $contact = Contact::create([
            'name' => 'Delete Me',
            'email' => 'delete@example.com',
            'message' => 'Delete test.',
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/contact/{$contact->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('contact_messages', ['id' => $contact->id]);
    }
}
