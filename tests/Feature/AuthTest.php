<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::forceCreate([
            'id' => 3,
            'name' => 'user',
        ]);
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertViewIs('auth.login');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'login@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertViewIs('auth.register');
    }
    /*Yangi foydalanuvchi qushish*/
    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'new-user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'new-user@example.com',
        ]);

        $user = User::where('email', 'new-user@example.com')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => 3,
        ]);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    public function test_register_requires_name_email_and_password(): void
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 1);
        $this->assertGuest();
    }

    public function test_register_requires_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'new-user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', [
            'email' => 'new-user@example.com',
        ]);
        $this->assertGuest();
    }

    public function test_register_requires_password_to_be_at_least_eight_characters(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'new-user@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', [
            'email' => 'new-user@example.com',
        ]);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('main'));
        $this->assertGuest();
    }
}
