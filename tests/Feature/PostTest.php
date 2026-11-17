<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
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

    public function test_blogger_can_open_create_post_page(): void
    {
        $role = Role::firstOrCreate(['name' => 'blogger']);
        $this->user->roles()->attach($role->id);

        $response = $this->actingAs($this->user)
            ->get(route('posts.create'));

        $response->assertOk();
        $response->assertViewIs('posts.create');
    }

    public function test_user_without_role_cannot_create_post(): void
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

        $response->assertForbidden();
        $this->assertDatabaseCount('posts', 0);
    }

    // Yangi Post bazaga yaratiladimi   CRETAE
    public function test_user_can_create_post(): void
    {
        $role = Role::firstOrCreate(['name' => 'blogger']);
        $this->user->roles()->attach($role->id);

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

    // Post tahrirlash sahifasi ochilishi EDIT
    public function test_user_can_open_edit_post_page(): void
    {
        $role = Role::firstOrCreate(['name' => 'blogger']);
        $this->user->roles()->attach($role->id);

        $tag = Tag::create(['name' => 'Laravel']);

        $post = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Test post',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ]);
        $post->tags()->attach($tag->id);

        $response = $this->actingAs($this->user)
            ->get(route('posts.edit', $post));

        $response->assertOk();
        $response->assertViewIs('posts.edit');
        $response->assertSee('Test post');
        $response->assertSee('Test Category');
        $response->assertSee('Laravel');
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

    public function test_blogger_can_update_own_post_without_replacing_image(): void
    {
        $role = Role::firstOrCreate(['name' => 'blogger']);
        $this->user->roles()->attach($role->id);

        Storage::fake('public');
        Storage::disk('public')->put('post-images/old.jpg', 'old');

        $post = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Oldingi sarlavha',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
            'image' => 'post-images/old.jpg',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('posts.update', $post), [
                '_method' => 'PUT',
                'title' => 'Yangi sarlavha',
                'category_id' => $this->category->id,
                'short_content' => 'Yangi qisqa mazmun',
                'content' => 'Yangi to‘liq mazmun',
                'price' => 200,
                'duration' => 4,
            ]);

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Yangi sarlavha',
            'image' => 'post-images/old.jpg',
        ]);
        Storage::disk('public')->assertExists('post-images/old.jpg');
    }

    public function test_editor_can_update_post_tags(): void
    {
        $role = Role::firstOrCreate(['name' => 'editor']);
        $this->user->roles()->attach($role->id);

        $tag = Tag::create(['name' => 'PHP']);

        $post = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Oldingi sarlavha',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('posts.update', $post), [
                'title' => 'Yangi sarlavha',
                'category_id' => $this->category->id,
                'short_content' => 'Yangi qisqa mazmun',
                'content' => 'Yangi to‘liq mazmun',
                'price' => 200,
                'duration' => 4,
                'tags' => [$tag->id],
            ]);

        $response->assertRedirect(route('posts.index'));
        $this->assertTrue($post->fresh()->tags->contains($tag));
    }

    public function test_blogger_cannot_update_another_users_post(): void
    {
        $role = Role::firstOrCreate(['name' => 'blogger']);
        $this->user->roles()->attach($role->id);

        $other = User::factory()->create();

        $post = Post::create([
            'user_id' => $other->id,
            'category_id' => $this->category->id,
            'title' => 'Boshqa foydalanuvchi posti',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('posts.update', $post), [
                'title' => 'Ruxsatsiz sarlavha',
                'category_id' => $this->category->id,
                'short_content' => 'Yangi qisqa mazmun',
                'content' => 'Yangi to‘liq mazmun',
                'price' => 200,
                'duration' => 4,
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Boshqa foydalanuvchi posti',
        ]);
    }

    public function test_updating_image_replaces_old_file(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $this->user->roles()->attach($role->id);

        Storage::fake('public');
        Storage::disk('public')->put('post-images/old.jpg', 'old');

        $post = Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Oldingi sarlavha',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
            'image' => 'post-images/old.jpg',
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('posts.update', $post), [
                'title' => 'Yangi sarlavha',
                'category_id' => $this->category->id,
                'short_content' => 'Yangi qisqa mazmun',
                'content' => 'Yangi to‘liq mazmun',
                'price' => 200,
                'duration' => 4,
                'image' => UploadedFile::fake()->image('new.jpg'),
            ]);

        $response->assertRedirect(route('posts.index'));

        $post->refresh();

        $this->assertNotSame('post-images/old.jpg', $post->image);
        Storage::disk('public')->assertMissing('post-images/old.jpg');
        Storage::disk('public')->assertExists($post->image);
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
