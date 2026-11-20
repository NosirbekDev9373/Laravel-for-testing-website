<?php

namespace Tests\Feature;

use App\Events\PostCreated as PostCreatedEvent;
use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use App\Notifications\PostDeletedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPostTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $editor;

    protected User $blogger;

    protected User $plainUser;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['name' => 'Admin User']);
        $this->editor = User::factory()->create(['name' => 'Editor User']);
        $this->blogger = User::factory()->create(['name' => 'Blogger User']);
        $this->plainUser = User::factory()->create(['name' => 'Plain User']);

        $this->admin->roles()->attach(Role::firstOrCreate(['name' => 'admin']));
        $this->editor->roles()->attach(Role::firstOrCreate(['name' => 'editor']));
        $this->blogger->roles()->attach(Role::firstOrCreate(['name' => 'blogger']));

        $this->category = Category::create(['name' => 'Admin kategoriya']);
    }

    private function createPost(User $user, string $title, ?Category $category = null, string $shortContent = 'Qisqa mazmun'): Post
    {
        return Post::create([
            'user_id' => $user->id,
            'category_id' => ($category ?? $this->category)->id,
            'title' => $title,
            'short_content' => $shortContent,
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ]);
    }

    public function test_guest_is_redirected_from_admin_posts(): void
    {
        $this->get(route('admin.posts.index'))->assertRedirect(route('login'));
    }

    public function test_non_staff_user_cannot_access_admin_panel(): void
    {
        $this->actingAs($this->plainUser)
            ->get(route('admin.posts.index'))
            ->assertForbidden();

        $this->actingAs($this->plainUser)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_blogger_sees_only_own_posts_in_admin(): void
    {
        $this->createPost($this->blogger, 'Mening postim');
        $this->createPost($this->editor, 'Editor posti');
        $this->createPost($this->admin, 'Admin posti');

        $response = $this->actingAs($this->blogger)->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertSee('Mening postim');
        $response->assertDontSee('Editor posti');
        $response->assertDontSee('Admin posti');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);
    }

    public function test_editor_sees_all_posts_in_admin(): void
    {
        $this->createPost($this->blogger, 'Blogger posti');
        $this->createPost($this->editor, 'Editor posti');
        $this->createPost($this->admin, 'Admin posti');

        $response = $this->actingAs($this->editor)->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertSee('Blogger posti');
        $response->assertSee('Editor posti');
        $response->assertSee('Admin posti');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 3);
    }

    public function test_blogger_can_manage_only_own_posts(): void
    {
        $ownPost = $this->createPost($this->blogger, 'O‘z postim');
        $otherPost = $this->createPost($this->editor, 'Boshqa post');

        $this->actingAs($this->blogger)
            ->get(route('admin.posts.edit', $ownPost))
            ->assertOk();

        $this->actingAs($this->blogger)
            ->get(route('admin.posts.edit', $otherPost))
            ->assertForbidden();

        $this->actingAs($this->blogger)
            ->delete(route('admin.posts.destroy', $otherPost))
            ->assertForbidden();

        $this->actingAs($this->blogger)
            ->delete(route('admin.posts.destroy', $ownPost))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $ownPost->id]);
        $this->assertDatabaseHas('posts', ['id' => $otherPost->id]);
    }

    public function test_editor_can_update_and_delete_any_post(): void
    {
        $otherPost = $this->createPost($this->admin, 'Admin posti');
        $ownPost = $this->createPost($this->editor, 'Editor posti');

        $this->actingAs($this->editor)
            ->get(route('admin.posts.edit', $otherPost))
            ->assertOk();

        $this->actingAs($this->editor)
            ->put(route('admin.posts.update', $otherPost), [
                'title' => 'Editor yangilagan post',
                'category_id' => $this->category->id,
                'short_content' => 'Yangi qisqa mazmun',
                'content' => 'Yangi to‘liq mazmun',
                'price' => 200,
                'duration' => 4,
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $otherPost->id,
            'title' => 'Editor yangilagan post',
        ]);

        $this->actingAs($this->editor)
            ->delete(route('admin.posts.destroy', $otherPost))
            ->assertRedirect(route('admin.posts.index'));

        $this->actingAs($this->editor)
            ->delete(route('admin.posts.destroy', $ownPost))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $otherPost->id]);
        $this->assertDatabaseMissing('posts', ['id' => $ownPost->id]);
    }

    public function test_blogger_can_create_post_via_admin(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->blogger)->post(route('admin.posts.store'), [
            'title' => 'Blogger yaratgan post',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 2,
            'image' => UploadedFile::fake()->image('post.jpg'),
        ]);

        $response->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Blogger yaratgan post',
            'user_id' => $this->blogger->id,
        ]);
    }

    public function test_admin_delete_removes_all_images_from_storage(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('post-images/admin-delete-main.jpg', 'main');
        Storage::disk('public')->put('post-images/admin-delete-hover.jpg', 'hover');
        Storage::disk('public')->put('post-images/admin-delete-gallery.jpg', 'gallery');

        $post = $this->createPost($this->editor, 'Admin o‘chiradigan post');
        $post->update([
            'image' => 'post-images/admin-delete-main.jpg',
            'image_hover' => 'post-images/admin-delete-hover.jpg',
            'gallery_images' => ['post-images/admin-delete-gallery.jpg'],
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        Storage::disk('public')->assertMissing('post-images/admin-delete-main.jpg');
        Storage::disk('public')->assertMissing('post-images/admin-delete-hover.jpg');
        Storage::disk('public')->assertMissing('post-images/admin-delete-gallery.jpg');
    }

    public function test_admin_can_delete_any_post(): void
    {
        $post = $this->createPost($this->editor, 'O‘chiriladigan post');

        $this->actingAs($this->admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_non_admin_cannot_access_admin_only_sections(): void
    {
        $this->actingAs($this->editor)
            ->get(route('admin.categories.index'))
            ->assertForbidden();

        $this->actingAs($this->blogger)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_admin_can_search_posts_by_title_and_content(): void
    {
        $this->createPost($this->blogger, 'Laravel bo‘yicha qo‘llanma', null, 'Laravel haqida qisqa matn');
        $this->createPost($this->editor, 'Boshqa post', null, 'Boshqa haqida qisqa matn');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.index', ['search' => 'Laravel']));

        $response->assertOk();
        $response->assertSee('Laravel bo‘yicha qo‘llanma');
        $response->assertDontSee('Boshqa post');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.index', ['search' => 'Boshqa haqida']));

        $response->assertSee('Boshqa post');
        $response->assertDontSee('Laravel bo‘yicha qo‘llanma');
    }

    public function test_admin_can_search_posts_by_author(): void
    {
        $this->createPost($this->blogger, 'Blogger posti');
        $this->createPost($this->editor, 'Editor posti');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.index', ['search' => 'Editor User']));

        $response->assertOk();
        $response->assertSee('Editor posti');
        $response->assertDontSee('Blogger posti');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);
    }

    public function test_admin_can_filter_posts_by_category(): void
    {
        $otherCategory = Category::create(['name' => 'Boshqa kategoriya']);

        $this->createPost($this->blogger, 'Asosiy kategoriya posti');
        $this->createPost($this->editor, 'Boshqa kategoriya posti', $otherCategory);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.index', ['category' => $otherCategory->id]));

        $response->assertOk();
        $response->assertSee('Boshqa kategoriya posti');
        $response->assertDontSee('Asosiy kategoriya posti');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);
    }

    public function test_search_and_category_filter_can_be_combined(): void
    {
        $otherCategory = Category::create(['name' => 'Boshqa kategoriya']);

        $this->createPost($this->blogger, 'Laravel asosiy post');
        $this->createPost($this->editor, 'Laravel boshqa post', $otherCategory);
        $this->createPost($this->editor, 'Boshqa mavzu posti', $otherCategory);

        $response = $this->actingAs($this->admin)->get(route('admin.posts.index', [
            'search' => 'Laravel',
            'category' => $otherCategory->id,
        ]));

        $response->assertOk();
        $response->assertSee('Laravel boshqa post');
        $response->assertDontSee('Laravel asosiy post');
        $response->assertDontSee('Boshqa mavzu posti');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);
    }

    public function test_blogger_search_only_includes_own_posts(): void
    {
        $this->createPost($this->blogger, 'Laravel mening postim');
        $this->createPost($this->editor, 'Laravel editor posti');

        $response = $this->actingAs($this->blogger)
            ->get(route('admin.posts.index', ['search' => 'Laravel']));

        $response->assertOk();
        $response->assertSee('Laravel mening postim');
        $response->assertDontSee('Laravel editor posti');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);
    }

    public function test_invalid_category_filter_is_ignored(): void
    {
        $this->createPost($this->blogger, 'Ko‘rinadigan post');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.index', ['category' => 'not-a-number']));

        $response->assertOk();
        $response->assertSee('Ko‘rinadigan post');
        $response->assertViewHas('categoryId', null);
    }

    public function test_admin_cannot_create_post_without_main_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Rasmsiz post',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 0,
        ])->assertSessionHasErrors('image');

        $this->assertDatabaseMissing('posts', ['title' => 'Rasmsiz post']);
    }

    public function test_admin_create_dispatches_event_and_notifies_creator(): void
    {
        Storage::fake('public');
        Event::fake([PostCreatedEvent::class]);
        Notification::fake();

        $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Eventli post',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 0,
            'image' => UploadedFile::fake()->image('post.jpg'),
        ])->assertRedirect(route('admin.posts.index'));

        Event::assertDispatched(PostCreatedEvent::class);
        Notification::assertSentTo($this->admin, PostCreatedNotification::class);
    }

    public function test_admin_delete_notifies_post_owner(): void
    {
        Notification::fake();

        $post = $this->createPost($this->blogger, 'O‘chiriladigan event posti');

        $this->actingAs($this->admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        Notification::assertSentTo($this->blogger, PostDeletedNotification::class);
    }

    public function test_admin_can_create_post_without_duration(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Davomiyliksiz post',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => '',
            'image' => UploadedFile::fake()->image('post.jpg'),
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Davomiyliksiz post',
            'duration' => 0,
        ]);
    }

    public function test_admin_can_remove_main_and_hover_images(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('post-images/old-main.jpg', 'main');
        Storage::disk('public')->put('post-images/old-hover.jpg', 'hover');

        $post = $this->createPost($this->admin, 'Rasmlari bor post');
        $post->update([
            'image' => 'post-images/old-main.jpg',
            'image_hover' => 'post-images/old-hover.jpg',
        ]);

        $this->actingAs($this->admin)->put(route('admin.posts.update', $post), [
            'title' => 'Rasmlar olib tashlandi',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 0,
            'remove_image' => 1,
            'remove_image_hover' => 1,
        ])->assertRedirect(route('admin.posts.index'));

        $post->refresh();

        $this->assertNull($post->image);
        $this->assertNull($post->image_hover);
        Storage::disk('public')->assertMissing('post-images/old-main.jpg');
        Storage::disk('public')->assertMissing('post-images/old-hover.jpg');
    }

    public function test_admin_can_append_and_remove_gallery_images(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('post-images/gallery-old-1.jpg', 'one');
        Storage::disk('public')->put('post-images/gallery-old-2.jpg', 'two');

        $post = $this->createPost($this->admin, 'Galereyali post');
        $post->update([
            'gallery_images' => [
                'post-images/gallery-old-1.jpg',
                'post-images/gallery-old-2.jpg',
            ],
        ]);

        $this->actingAs($this->admin)->put(route('admin.posts.update', $post), [
            'title' => 'Galereya yangilandi',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 0,
            'remove_gallery' => ['post-images/gallery-old-1.jpg'],
            'gallery_images' => [UploadedFile::fake()->image('gallery-new.jpg')],
        ])->assertRedirect(route('admin.posts.index'));

        $post->refresh();

        $this->assertCount(2, $post->gallery_images);
        $this->assertContains('post-images/gallery-old-2.jpg', $post->gallery_images);
        $this->assertNotContains('post-images/gallery-old-1.jpg', $post->gallery_images);

        $newPath = collect($post->gallery_images)->first(
            fn ($path) => ! in_array($path, ['post-images/gallery-old-1.jpg', 'post-images/gallery-old-2.jpg'], true)
        );

        $this->assertNotNull($newPath);
        Storage::disk('public')->assertMissing('post-images/gallery-old-1.jpg');
        Storage::disk('public')->assertExists('post-images/gallery-old-2.jpg');
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_remove_gallery_only_accepts_existing_post_images(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('post-images/gallery-own.jpg', 'own');
        Storage::disk('public')->put('post-images/gallery-other.jpg', 'other');

        $post = $this->createPost($this->admin, 'Himoyalangan galereya');
        $post->update(['gallery_images' => ['post-images/gallery-own.jpg']]);

        $this->actingAs($this->admin)->put(route('admin.posts.update', $post), [
            'title' => 'Himoyalangan galereya',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 0,
            'remove_gallery' => ['post-images/gallery-other.jpg'],
        ])->assertRedirect(route('admin.posts.index'));

        $post->refresh();

        $this->assertSame(['post-images/gallery-own.jpg'], $post->gallery_images);
        Storage::disk('public')->assertExists('post-images/gallery-own.jpg');
        Storage::disk('public')->assertExists('post-images/gallery-other.jpg');
    }

    public function test_admin_cannot_create_post_with_more_than_four_gallery_images(): void
    {
        Storage::fake('public');

        $gallery = [];

        for ($i = 1; $i <= 5; $i++) {
            $gallery[] = UploadedFile::fake()->image('gallery-'.$i.'.jpg');
        }

        $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Ko‘p rasmlik admin posti',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 0,
            'image' => UploadedFile::fake()->image('post.jpg'),
            'gallery_images' => $gallery,
        ])->assertSessionHasErrors('gallery_images');

        $this->assertDatabaseMissing('posts', ['title' => 'Ko‘p rasmlik admin posti']);
    }

    public function test_admin_cannot_exceed_four_gallery_images_on_update(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('post-images/gallery-old-1.jpg', 'one');
        Storage::disk('public')->put('post-images/gallery-old-2.jpg', 'two');
        Storage::disk('public')->put('post-images/gallery-old-3.jpg', 'three');

        $post = $this->createPost($this->admin, 'Uch rasmlik post');
        $post->update([
            'gallery_images' => [
                'post-images/gallery-old-1.jpg',
                'post-images/gallery-old-2.jpg',
                'post-images/gallery-old-3.jpg',
            ],
        ]);

        $this->actingAs($this->admin)->put(route('admin.posts.update', $post), [
            'title' => 'Uch rasmlik post',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 0,
            'gallery_images' => [
                UploadedFile::fake()->image('new-1.jpg'),
                UploadedFile::fake()->image('new-2.jpg'),
            ],
        ])->assertSessionHasErrors('gallery_images');

        $post->refresh();

        $this->assertCount(3, $post->gallery_images);
    }

    public function test_admin_post_form_shows_validation_errors(): void
    {
        $response = $this->actingAs($this->admin)
            ->followingRedirects()
            ->from(route('admin.posts.create'))
            ->post(route('admin.posts.store'), []);

        $response->assertSee('Formada xatolar bor');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_admin_post_changes_clear_public_index_cache(): void
    {
        Cache::flush();
        Storage::fake('public');

        $this->get(route('posts.index'));

        $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Cache test posti',
            'category_id' => $this->category->id,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 2,
            'image' => UploadedFile::fake()->image('post.jpg'),
        ])->assertRedirect(route('admin.posts.index'));

        $this->get(route('posts.index'))->assertSee('Cache test posti');

        $post = Post::where('title', 'Cache test posti')->firstOrFail();

        $this->actingAs($this->admin)->put(route('admin.posts.update', $post), [
            'title' => 'Yangilangan cache posti',
            'category_id' => $this->category->id,
            'short_content' => 'Yangilangan qisqa mazmun',
            'content' => 'Yangilangan to‘liq mazmun',
            'price' => 200,
            'duration' => 3,
        ])->assertRedirect(route('admin.posts.index'));

        $this->get(route('posts.index'))
            ->assertSee('Yangilangan cache posti')
            ->assertDontSee('Cache test posti');

        $this->actingAs($this->admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->get(route('posts.index'))->assertDontSee('Yangilangan cache posti');
    }

    public function test_blogger_sees_limited_admin_dashboard(): void
    {
        $this->createPost($this->blogger, 'Dashboard postim');

        $response = $this->actingAs($this->blogger)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard postim');
        $response->assertDontSee('Yangi foydalanuvchilar');
        $response->assertDontSee(route('admin.users.index'), false);
    }
}
