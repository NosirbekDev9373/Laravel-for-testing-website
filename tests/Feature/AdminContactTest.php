<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContactTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Contact',
            'email' => 'admin-contact@example.test',
        ]);
        $this->admin->roles()->attach(Role::firstOrCreate(['name' => 'admin'])->id);
    }

    private function createContact(array $overrides = []): ContactMessage
    {
        return ContactMessage::create(array_merge([
            'name' => 'Ali Valiyev',
            'email' => 'ali@example.test',
            'service_type' => 'Tozalash',
            'message' => 'Xizmat haqida savol',
            'ip_address' => '127.0.0.1',
        ], $overrides));
    }

    public function test_admin_can_view_contact_messages(): void
    {
        $this->createContact(['name' => 'Anvar Test', 'message' => 'Noyob xabar matni']);

        $response = $this->actingAs($this->admin)->get(route('admin.contacts.index'));

        $response->assertOk();
        $response->assertSee('Anvar Test');
        $response->assertSee('Noyob xabar matni');
    }

    public function test_non_admin_cannot_view_contact_messages(): void
    {
        $plainUser = User::factory()->create();

        $this->actingAs($plainUser)
            ->get(route('admin.contacts.index'))
            ->assertForbidden();
    }

    public function test_admin_can_search_contact_messages(): void
    {
        $this->createContact(['name' => 'Anvar Search', 'email' => 'anvar-search@example.test']);
        $this->createContact(['name' => 'Boshqa Search', 'email' => 'boshqa-search@example.test']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.contacts.index', ['search' => 'anvar']));

        $response->assertOk();
        $response->assertSee('anvar-search@example.test');
        $response->assertDontSee('boshqa-search@example.test');
    }

    public function test_admin_can_filter_contact_messages_by_status(): void
    {
        $unread = $this->createContact(['message' => 'O‘qilmagan xabar']);
        $read = $this->createContact(['message' => 'O‘qilgan xabar']);
        $read->forceFill(['read_at' => now()])->save();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.contacts.index', ['status' => 'unread']));

        $response->assertOk();
        $response->assertSee('O‘qilmagan xabar');
        $response->assertDontSee('O‘qilgan xabar');
        $response->assertViewHas('contacts', fn ($contacts) => $contacts->total() === 1);
    }

    public function test_opening_contact_message_marks_it_as_read(): void
    {
        $contact = $this->createContact();

        $this->assertNull($contact->read_at);

        $response = $this->actingAs($this->admin)->get(route('admin.contacts.show', $contact));

        $response->assertOk();
        $response->assertSee('Xizmat haqida savol');
        $this->assertNotNull($contact->refresh()->read_at);
    }

    public function test_admin_can_delete_contact_message(): void
    {
        $contact = $this->createContact();

        $this->actingAs($this->admin)
            ->delete(route('admin.contacts.destroy', $contact))
            ->assertRedirect(route('admin.contacts.index'));

        $this->assertDatabaseMissing('contact_messages', ['id' => $contact->id]);
    }

    public function test_dashboard_shows_unread_contact_count(): void
    {
        $this->createContact(['message' => 'Birinchi o‘qilmagan']);
        $this->createContact(['message' => 'Ikkinchi o‘qilmagan']);

        $read = $this->createContact(['message' => 'O‘qilgan xabar']);
        $read->forceFill(['read_at' => now()])->save();

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('O‘qilmagan xabarlar');
        $response->assertViewHas('unreadContactsCount', 2);
    }
}
