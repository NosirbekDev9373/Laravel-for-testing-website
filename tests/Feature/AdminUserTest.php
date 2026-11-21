<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Role $adminRole;

    protected Role $editorRole;

    protected Role $bloggerRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->editorRole = Role::firstOrCreate(['name' => 'editor']);
        $this->bloggerRole = Role::firstOrCreate(['name' => 'blogger']);

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.test',
        ]);
        $this->admin->roles()->attach($this->adminRole->id);
    }

    public function test_admin_can_view_users_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('admin@example.test');
    }

    public function test_admin_can_search_users_case_insensitively(): void
    {
        User::factory()->create(['name' => 'Anvarjon Test', 'email' => 'anvar@example.test']);
        User::factory()->create(['name' => 'Boshqa Name', 'email' => 'boshqa@example.test']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'anvar']));

        $response->assertOk();
        $response->assertSee('anvar@example.test');
        $response->assertDontSee('boshqa@example.test');
    }

    public function test_admin_can_filter_users_by_role(): void
    {
        $editor = User::factory()->create(['name' => 'Editor Filter', 'email' => 'editor-filter@example.test']);
        $blogger = User::factory()->create(['name' => 'Blogger Filter', 'email' => 'blogger-filter@example.test']);
        $editor->roles()->attach($this->editorRole->id);
        $blogger->roles()->attach($this->bloggerRole->id);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => $this->editorRole->id]));

        $response->assertOk();
        $response->assertSee('editor-filter@example.test');
        $response->assertDontSee('blogger-filter@example.test');
    }

    public function test_admin_can_update_user_roles(): void
    {
        $user = User::factory()->create(['name' => 'Role Update', 'email' => 'role-update@example.test']);
        $user->roles()->attach($this->bloggerRole->id);

        $this->actingAs($this->admin)
            ->put(route('admin.users.roles', $user), ['roles' => [$this->editorRole->id]])
            ->assertRedirect();

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $this->editorRole->id,
        ]);
        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $this->bloggerRole->id,
        ]);
    }

    public function test_admin_cannot_assign_invalid_role(): void
    {
        $user = User::factory()->create(['name' => 'Invalid Role', 'email' => 'invalid-role@example.test']);
        $user->roles()->attach($this->bloggerRole->id);

        $this->actingAs($this->admin)
            ->put(route('admin.users.roles', $user), ['roles' => [999999]])
            ->assertSessionHasErrors();

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $this->bloggerRole->id,
        ]);
    }

    public function test_admin_cannot_remove_own_admin_role(): void
    {
        $this->actingAs($this->admin)
            ->put(route('admin.users.roles', $this->admin), ['roles' => [$this->editorRole->id]])
            ->assertSessionHasErrors('roles');

        $this->assertDatabaseHas('role_user', [
            'user_id' => $this->admin->id,
            'role_id' => $this->adminRole->id,
        ]);
    }

    public function test_admin_cannot_delete_user_with_posts_or_comments(): void
    {
        $user = User::factory()->create(['name' => 'Postli User', 'email' => 'postli@example.test']);
        $category = Category::create(['name' => 'User kategoriya']);

        $post = Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'User posti',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 1,
        ]);

        Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body' => 'User izohi',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_admin_can_delete_plain_user(): void
    {
        $user = User::factory()->create(['name' => 'Oddiy User', 'email' => 'oddiy@example.test']);
        $user->roles()->attach($this->bloggerRole->id);

        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('role_user', ['user_id' => $user->id]);
    }

    public function test_admin_user_form_shows_validation_errors(): void
    {
        $response = $this->actingAs($this->admin)
            ->followingRedirects()
            ->from(route('admin.users.index'))
            ->put(route('admin.users.roles', $this->admin), ['roles' => [999999]]);

        $response->assertSee('Formada xatolar bor');
    }
}
