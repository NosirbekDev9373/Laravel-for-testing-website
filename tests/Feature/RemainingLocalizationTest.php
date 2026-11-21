<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemainingLocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->roles()->attach(Role::firstOrCreate(['name' => 'blogger'])->id);

        $this->category = Category::create(['name' => 'Tozalash']);
    }

    private function createPost(string $title): Post
    {
        return Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => $title,
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 3,
        ]);
    }

    public function test_post_detail_page_is_translated_to_english(): void
    {
        $post = $this->createPost('Asosiy post');
        $this->createPost('Boshqa post');

        $this->get(route('locale.change', 'en'));
        $response = $this->actingAs($this->user)->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('Category');
        $response->assertSee('Author');
        $response->assertSee('Comments');
        $response->assertSee('Leave a comment');
        $response->assertSee('Related posts');
        $response->assertSee('3 hrs');
        $response->assertDontSee('Izoh qoldiring');
    }

    public function test_post_detail_page_is_translated_to_russian(): void
    {
        $post = $this->createPost('Asosiy post');
        $this->createPost('Boshqa post');

        $this->get(route('locale.change', 'ru'));
        $response = $this->actingAs($this->user)->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('Категория');
        $response->assertSee('Автор');
        $response->assertSee('Комментарии');
        $response->assertSee('Оставить комментарий');
        $response->assertSee('Похожие посты');
        $response->assertSee('3 ч');
        $response->assertDontSee('Izoh qoldiring');
    }

    public function test_post_create_form_is_translated(): void
    {
        $this->get(route('locale.change', 'en'));

        $response = $this->actingAs($this->user)->get(route('posts.create'));

        $response->assertOk();
        $response->assertSee('Create new post');
        $response->assertSee('Title');
        $response->assertSee('Main image');
        $response->assertSee('Save');
        $response->assertDontSee('Yangi post yaratish');
    }

    public function test_post_edit_form_is_translated(): void
    {
        $post = $this->createPost('Tahrir posti');

        $this->get(route('locale.change', 'ru'));

        $response = $this->actingAs($this->user)->get(route('posts.edit', $post));

        $response->assertOk();
        $response->assertSee('Редактировать пост');
        $response->assertSee('Заголовок');
        $response->assertSee('Отмена');
        $response->assertDontSee("Postni o'zgartirish");
    }

    public function test_auth_pages_are_translated(): void
    {
        $this->get(route('locale.change', 'en'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Password')
            ->assertSee('Remember me')
            ->assertSee("Don't have an account?");

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Confirm password')
            ->assertSee('Already have an account?');
    }

    public function test_notifications_page_is_translated(): void
    {
        $this->get(route('locale.change', 'ru'));

        $response = $this->actingAs($this->user)->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee('Уведомления');
        $response->assertSee('Пока нет уведомлений.');
        $response->assertDontSee('Bildirishnomalar');
    }
}
