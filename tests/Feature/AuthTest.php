<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
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
        $response->assertSessionHasErrors([
            'email' => __('auth.failed'),
        ]);
        $this->assertGuest();
    }

    public function test_login_remembers_user_when_remember_is_checked(): void
    {
        $user = User::factory()->create([
            'email' => 'remember@example.com',
            'password' => bcrypt('password123'),
            'remember_token' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'remember@example.com',
            'password' => 'password123',
            'remember' => '1',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->remember_token);
    }

    public function test_login_is_blocked_after_five_failed_attempts(): void
    {
        User::factory()->create([
            'email' => 'throttle@example.com',
            'password' => bcrypt('password123'),
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->from('/login')->post('/login', [
                'email' => 'throttle@example.com',
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');
        }

        $response = $this->from('/login')->post('/login', [
            'email' => 'throttle@example.com',
            'password' => 'password123',
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

    /* Yangi foydalanuvchi qushish */
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
            'role_id' => Role::where('name', 'user')->value('id'),
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

    public function test_register_is_blocked_after_five_attempts(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->from('/register')->post('/register', [
                'name' => 'Spam User',
                'email' => "spam{$attempt}@example.com",
                'password' => 'short',
                'password_confirmation' => 'short',
            ])->assertSessionHasErrors('password');
        }

        $response = $this->from('/register')->post('/register', [
            'name' => 'Spam User',
            'email' => 'spam@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', [
            'email' => 'spam@example.com',
        ]);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('main'));
        $this->assertGuest();
    }
}
