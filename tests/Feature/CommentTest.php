<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin foydalanuvchi yaratamiz
        $this->user = User::factory()->create();

        $role = Role::create([
            'name' => 'admin',
        ]);

        $this->user->roles()->attach($role->id);

        // Category yaratamiz
        $category = Category::create([
            'name' => 'Test Category',
        ]);

        // Post yaratamiz
        $this->post = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => 'Test Post',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 5,
        ]);
    }

    // CREATE
    public function test_user_can_create_comment(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('comments.store'), [
                'post_id' => $this->post->id,
                'body' => 'Bu mening birinchi izohim',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => 'Bu mening birinchi izohim',
        ]);
    }

    // READ
    public function test_admin_can_view_comments(): void
    {
        Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => 'Ko‘rish uchun test izohi',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.comments.index'));

        $response->assertOk();

        $response->assertSee('Ko‘rish uchun test izohi');
    }

    // DELETE
    public function test_admin_can_delete_comment(): void
    {
        $comment = Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => 'O‘chiriladigan izoh',
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('admin.comments.destroy', $comment));

        $response->assertRedirect();

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
