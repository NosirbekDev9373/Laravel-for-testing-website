<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Queue;
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
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => 'Bu mening birinchi izohim',
        ]);
    }

    public function test_comment_body_cannot_exceed_two_thousand_characters(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('comments.store'), [
                'post_id' => $this->post->id,
                'body' => str_repeat('a', 2001),
            ]);

        $response->assertSessionHasErrors('body');
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_requires_valid_post_id(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('comments.store'), [
                'post_id' => 999999,
                'body' => 'Bu izoh saqlanmasligi kerak',
            ]);

        $response->assertSessionHasErrors('post_id');
        $this->assertDatabaseCount('comments', 0);
    }

    // READ

    public function test_comment_notification_is_queued_instead_of_sent_synchronously(): void
    {
        Queue::fake();

        $owner = User::factory()->create();
        $this->post->update(['user_id' => $owner->id]);

        $this->actingAs($this->user)
            ->post(route('comments.store'), [
                'post_id' => $this->post->id,
                'body' => 'Sinxron bo‘lmagan izoh',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', ['body' => 'Sinxron bo‘lmagan izoh']);
        Queue::assertPushed(SendQueuedNotifications::class, 2);
    }

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
        $response->assertSee(route('posts.show', $this->post), false);
    }

    public function test_admin_can_search_comments_by_body(): void
    {
        Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => 'Noyob izoh matni',
        ]);
        Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => 'Boshqa oddiy izoh',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.comments.index', ['search' => 'noyob']));

        $response->assertOk();
        $response->assertSee('Noyob izoh matni');
        $response->assertDontSee('Boshqa oddiy izoh');
    }

    public function test_admin_can_search_comments_by_author(): void
    {
        $author = User::factory()->create(['name' => 'Anvar Authorson']);

        Comment::create([
            'user_id' => $author->id,
            'post_id' => $this->post->id,
            'body' => 'Anvar yozgan izoh',
        ]);
        Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => 'Boshqa izoh matni',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.comments.index', ['search' => 'anvar']));

        $response->assertOk();
        $response->assertSee('Anvar yozgan izoh');
        $response->assertDontSee('Boshqa izoh matni');
    }

    public function test_admin_can_search_comments_by_post_title(): void
    {
        $otherPost = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->post->category_id,
            'title' => 'Noyob Post Sarlavhasi',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 5,
        ]);

        Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $otherPost->id,
            'body' => 'Bog‘langan izoh',
        ]);
        Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => 'Kamarak izoh',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.comments.index', ['search' => 'noyob post']));

        $response->assertOk();
        $response->assertSee('Bog‘langan izoh');
        $response->assertDontSee('Kamarak izoh');
    }

    public function test_non_admin_cannot_view_admin_comments(): void
    {
        $plainUser = User::factory()->create();

        $this->actingAs($plainUser)
            ->get(route('admin.comments.index'))
            ->assertForbidden();
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
