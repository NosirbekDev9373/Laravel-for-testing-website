<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->category = Category::create([
            'name' => 'Test Category',
        ]);
    }

    // Yangi Post bazaga yaratiladimi   CRETAE
    public function test_user_can_create_post(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post(route('posts.store'), [
                'title' => 'Test post',
                'category_id' => $this->category->id,
                'short_content' => 'Test qisqa mazmun',
                'content' => 'Test to‘liq mazmun',
                'price' => 150,
                'duration' => 3,
                'image' => UploadedFile::fake()->image('post.jpg'),
            ]);

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Test post',
            'user_id' => $this->user->id,
        ]);
    }

    // Postlar royxatini ochilishi READ
    public function test_posts_can_be_viewed(): void
    {
        $post = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Test post',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ]);

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertViewIs('posts.index');
        $response->assertSee('Test post');
    }

    // Post malumotlari uzgartirillishi UPDATE
    public function test_user_can_update_post(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $this->user->roles()->attach($role->id);

        $post = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Oldingi sarlavha',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ]);

        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->put(route('posts.update', $post), [
                'title' => 'Yangi sarlavha',
                'category_id' => $this->category->id,
                'short_content' => 'Yangi qisqa mazmun',
                'content' => 'Yangi to‘liq mazmun',
                'price' => 200,
                'duration' => 4,
                'image' => UploadedFile::fake()->image('new-post.jpg'),
            ]);

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Yangi sarlavha',
            'price' => 200,
        ]);
    }

    // Post uchirilishi DELETE
    public function test_user_can_delete_post(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $this->user->roles()->attach($role->id);

        $post = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'O‘chiriladigan post',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
