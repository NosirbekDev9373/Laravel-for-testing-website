<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
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

        Cache::flush();

        $this->user = User::factory()->create();

        $this->category = Category::create([
            'name' => 'Test Category',
        ]);
    }

    private function createPost(array $overrides = []): Post
    {
        return Post::create(array_merge([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Test post',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ], $overrides));
    }

    public function test_home_page_shows_latest_posts(): void
    {
        $posts = [];

        for ($i = 1; $i <= 5; $i++) {
            $posts[] = $this->createPost(['title' => 'Home post '.$i]);
        }

        $response = $this->get(route('main'));

        $response->assertOk();
        $response->assertSee('Home post 5');
        $response->assertSee('Home post 2');
        $response->assertDontSee('Home post 1');
        $response->assertSee(route('posts.show', $posts[4]), false);
        $response->assertDontSee('services-detail.html');
        $response->assertViewHas('posts', fn ($homePosts) => $homePosts->count() === 4);
    }

    public function test_home_page_shows_empty_state_without_posts(): void
    {
        $response = $this->get(route('main'));

        $response->assertOk();
        $response->assertSee('Hozircha postlar yo‘q.');
    }

    public function test_home_page_shows_real_categories_and_comments(): void
    {
        $post = $this->createPost(['title' => 'Kategoriya posti']);

        Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $post->id,
            'body' => 'Bu haqiqiy foydalanuvchi izohi',
        ]);

        $response = $this->get(route('main'));

        $response->assertOk();
        $response->assertSee('Kategoriyalar');
        $response->assertSee($this->category->name);
        $response->assertSee(route('posts.index', ['category' => $this->category->id]), false);
        $response->assertSee('Foydalanuvchilar fikrlari');
        $response->assertSee('Bu haqiqiy foydalanuvchi izohi');
        $response->assertSee($this->user->name);
        $response->assertSee(route('posts.show', $post), false);
    }

    public function test_home_page_hides_testimonials_without_comments(): void
    {
        $response = $this->get(route('main'));

        $response->assertOk();
        $response->assertDontSee('Foydalanuvchilar fikrlari');
    }

    public function test_home_page_does_not_contain_template_placeholder_content(): void
    {
        $response = $this->get(route('main'));

        $response->assertOk();
        $response->assertDontSee('Tooplate');
        $response->assertDontSee('110-220-9800');
        $response->assertDontSee('Happy Customers');
        $response->assertDontSee('services-detail.html');
    }

    public function test_posts_index_shows_empty_state(): void
    {
        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee('Hozircha postlar yo‘q.');
    }

    public function test_posts_index_filters_by_valid_category(): void
    {
        $otherCategory = Category::create(['name' => 'Other Category']);

        for ($i = 1; $i <= 5; $i++) {
            $this->createPost([
                'title' => 'Category post '.$i,
                'category_id' => $this->category->id,
            ]);
        }

        $this->createPost([
            'title' => 'Other post',
            'category_id' => $otherCategory->id,
        ]);

        $response = $this->get(route('posts.index', ['category' => $this->category->id]));

        $response->assertOk();
        $response->assertSee('Category post 5');
        $response->assertDontSee('Other post');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 5);
        $response->assertViewHas('categoryId', $this->category->id);
    }

    public function test_posts_index_shows_second_category_page(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->createPost([
                'title' => 'Category post '.$i,
            ]);
        }

        $response = $this->get(route('posts.index', [
            'category' => $this->category->id,
            'page' => 2,
        ]));

        $response->assertOk();
        $response->assertSee('Category post 1');
        $response->assertDontSee('Category post 5');
    }

    public function test_posts_index_ignores_invalid_category_filter(): void
    {
        $this->createPost(['title' => 'Visible post']);

        $response = $this->get(route('posts.index', ['category' => 999999]));

        $response->assertOk();
        $response->assertSee('Visible post');
        $response->assertViewHas('categoryId', null);
    }

    public function test_posts_index_searches_posts_by_title_and_content(): void
    {
        $this->createPost([
            'title' => 'Laravel uyi',
            'content' => 'Noyob mazmun matni',
        ]);
        $this->createPost(['title' => 'Boshqa post']);

        $response = $this->get(route('posts.index', ['search' => 'laravel']));

        $response->assertOk();
        $response->assertSee('Laravel uyi');
        $response->assertDontSee('Boshqa post');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);

        $response = $this->get(route('posts.index', ['search' => 'noyob mazmun']));

        $response->assertSee('Laravel uyi');
        $response->assertDontSee('Boshqa post');
    }

    public function test_posts_index_search_can_be_combined_with_category(): void
    {
        $otherCategory = Category::create(['name' => 'Boshqa kategoriya']);

        $this->createPost(['title' => 'Laravel mos post']);
        $this->createPost([
            'title' => 'Laravel boshqa post',
            'category_id' => $otherCategory->id,
        ]);

        $response = $this->get(route('posts.index', [
            'category' => $this->category->id,
            'search' => 'laravel',
        ]));

        $response->assertOk();
        $response->assertSee('Laravel mos post');
        $response->assertDontSee('Laravel boshqa post');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);
    }

    public function test_posts_index_searches_posts_by_category_name(): void
    {
        $this->createPost(['title' => 'Kategoriya qidiruvi posti']);

        $response = $this->get(route('posts.index', ['search' => 'test category']));

        $response->assertOk();
        $response->assertSee('Kategoriya qidiruvi posti');
    }

    public function test_posts_index_search_shows_empty_state(): void
    {
        $this->createPost(['title' => 'Oddiy post']);

        $response = $this->get(route('posts.index', ['search' => 'topilmadi']));

        $response->assertOk();
        $response->assertSee('Qidiruv bo‘yicha post topilmadi.');
        $response->assertDontSee('Oddiy post');
    }

    public function test_posts_index_uses_hover_image_when_available(): void
    {
        $this->createPost([
            'title' => 'Hover image post',
            'image' => 'post-images/main.jpg',
            'image_hover' => 'post-images/hover.jpg',
        ]);

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee(asset('storage/post-images/hover.jpg'), false);
    }

    public function test_posts_index_uses_placeholder_when_image_is_missing(): void
    {
        $this->createPost(['title' => 'No image post']);

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee(asset('images/post-placeholder.svg'), false);
    }

    public function test_show_excludes_current_post_from_related_posts(): void
    {
        $post = $this->createPost(['title' => 'Current post']);
        $relatedPost = $this->createPost(['title' => 'Related post']);

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertViewHas('related_services', function ($relatedPosts) use ($post, $relatedPost) {
            return $relatedPosts->pluck('id')->doesntContain($post->id)
                && $relatedPosts->pluck('id')->contains($relatedPost->id);
        });
        $response->assertSee('Related post');
    }

    public function test_show_uses_placeholder_when_image_is_missing(): void
    {
        $post = $this->createPost(['title' => 'No image post']);

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee(asset('images/post-placeholder.svg'), false);
    }

    public function test_show_paginates_comments(): void
    {
        $post = $this->createPost(['title' => 'Commented post']);

        for ($i = 1; $i <= 6; $i++) {
            Comment::create([
                'post_id' => $post->id,
                'user_id' => $this->user->id,
                'body' => 'Comment '.$i,
            ]);
        }

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('6 Izohlar');
        $response->assertSee('Comment 6');
        $response->assertDontSee('Comment 1');
        $response->assertViewHas('comments', function ($comments) {
            return $comments->count() === 5 && $comments->hasPages();
        });
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

    public function test_user_can_create_post_with_four_gallery_images(): void
    {
        $role = Role::firstOrCreate(['name' => 'blogger']);
        $this->user->roles()->attach($role->id);

        Storage::fake('public');

        $gallery = [];

        for ($i = 1; $i <= 4; $i++) {
            $gallery[] = UploadedFile::fake()->image('gallery-'.$i.'.jpg');
        }

        $this->actingAs($this->user)
            ->post(route('posts.store'), [
                'title' => 'Galereyali post',
                'category_id' => $this->category->id,
                'short_content' => 'Qisqa mazmun',
                'content' => 'To‘liq mazmun',
                'price' => 150,
                'duration' => 3,
                'image' => UploadedFile::fake()->image('post.jpg'),
                'gallery_images' => $gallery,
            ])
            ->assertRedirect(route('posts.index'));

        $post = Post::where('title', 'Galereyali post')->firstOrFail();

        $this->assertCount(4, $post->gallery_images);
    }

    public function test_user_cannot_create_post_with_more_than_four_gallery_images(): void
    {
        $role = Role::firstOrCreate(['name' => 'blogger']);
        $this->user->roles()->attach($role->id);

        Storage::fake('public');

        $gallery = [];

        for ($i = 1; $i <= 5; $i++) {
            $gallery[] = UploadedFile::fake()->image('gallery-'.$i.'.jpg');
        }

        $this->actingAs($this->user)
            ->post(route('posts.store'), [
                'title' => 'Ko‘p rasmlik post',
                'category_id' => $this->category->id,
                'short_content' => 'Qisqa mazmun',
                'content' => 'To‘liq mazmun',
                'price' => 150,
                'duration' => 3,
                'image' => UploadedFile::fake()->image('post.jpg'),
                'gallery_images' => $gallery,
            ])
            ->assertSessionHasErrors('gallery_images');

        $this->assertDatabaseMissing('posts', ['title' => 'Ko‘p rasmlik post']);
    }

    public function test_post_show_displays_gallery_images(): void
    {
        $post = $this->createPost([
            'title' => 'Galereyasi bor post',
            'gallery_images' => [
                'post-images/gallery-one.jpg',
                'post-images/gallery-two.jpg',
            ],
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('Galereya');
        $response->assertSee(asset('storage/post-images/gallery-one.jpg'), false);
        $response->assertSee(asset('storage/post-images/gallery-two.jpg'), false);
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

    public function test_editor_can_delete_another_users_post(): void
    {
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $this->user->roles()->attach($editorRole->id);

        $otherUser = User::factory()->create();

        $post = Post::create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id,
            'title' => 'Editor o‘chiradigan post',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 1,
        ]);

        $this->actingAs($this->user)
            ->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
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

    public function test_deleting_post_removes_all_images_from_storage(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $this->user->roles()->attach($role->id);

        Storage::fake('public');
        Storage::disk('public')->put('post-images/delete-main.jpg', 'main');
        Storage::disk('public')->put('post-images/delete-hover.jpg', 'hover');
        Storage::disk('public')->put('post-images/delete-gallery-1.jpg', 'gallery-1');
        Storage::disk('public')->put('post-images/delete-gallery-2.jpg', 'gallery-2');

        $post = $this->createPost([
            'title' => 'Rasmlari bilan o‘chiriladigan post',
            'image' => 'post-images/delete-main.jpg',
            'image_hover' => 'post-images/delete-hover.jpg',
            'gallery_images' => [
                'post-images/delete-gallery-1.jpg',
                'post-images/delete-gallery-2.jpg',
            ],
        ]);

        $this->actingAs($this->user)
            ->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        Storage::disk('public')->assertMissing('post-images/delete-main.jpg');
        Storage::disk('public')->assertMissing('post-images/delete-hover.jpg');
        Storage::disk('public')->assertMissing('post-images/delete-gallery-1.jpg');
        Storage::disk('public')->assertMissing('post-images/delete-gallery-2.jpg');
    }

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
