<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_shows_real_stats_and_authors(): void
    {
        $author = User::factory()->create(['name' => 'Muallif Ism']);

        $category = Category::create(['name' => 'Tozalash']);

        $post = Post::create([
            'user_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'About test posti',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 2,
        ]);

        Comment::create([
            'user_id' => $author->id,
            'post_id' => $post->id,
            'body' => 'About sahifasi uchun izoh',
        ]);

        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('Platforma statistikasi');
        $response->assertSee('Bizning mualliflar');
        $response->assertSee('Muallif Ism');
        $response->assertSee('1 ta post');
        $response->assertSee(route('contact'), false);
        $response->assertViewHas('stats', function (array $stats) {
            $values = collect($stats)->pluck('value', 'label');

            return $values['Postlar'] === 1
                && $values['Kategoriyalar'] === 1
                && $values['Foydalanuvchilar'] === 1
                && $values['Izohlar'] === 1;
        });
    }

    public function test_about_page_hides_authors_without_posts(): void
    {
        User::factory()->create();

        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('Platforma statistikasi');
        $response->assertDontSee('Bizning mualliflar');
    }

    public function test_about_page_shows_authors_from_all_roles(): void
    {
        $category = Category::create(['name' => 'Muallif kategoriyasi']);

        $roles = [
            'admin' => Role::firstOrCreate(['name' => 'admin']),
            'editor' => Role::firstOrCreate(['name' => 'editor']),
            'blogger' => Role::firstOrCreate(['name' => 'blogger']),
        ];

        foreach ($roles as $roleName => $role) {
            $author = User::factory()->create(['name' => ucfirst($roleName).' Muallif']);
            $author->roles()->attach($role->id);

            Post::create([
                'user_id' => $author->id,
                'category_id' => $category->id,
                'title' => ucfirst($roleName).' posti',
                'short_content' => 'Qisqa mazmun',
                'content' => 'To‘liq mazmun',
                'price' => 100,
                'duration' => 2,
            ]);
        }

        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('Admin Muallif');
        $response->assertSee('Editor Muallif');
        $response->assertSee('Blogger Muallif');
        $response->assertSee('Admin');
        $response->assertSee('Editor');
        $response->assertSee('Blogger');
        $response->assertViewHas('authors', fn ($authors) => $authors->count() === 3);
    }

    public function test_about_page_shows_role_users_even_without_posts(): void
    {
        $blogger = User::factory()->create(['name' => 'Blogger Postsiz']);
        $blogger->roles()->attach(Role::firstOrCreate(['name' => 'blogger'])->id);

        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('Bizning mualliflar');
        $response->assertSee('Blogger Postsiz');
        $response->assertSee('Blogger');
        $response->assertSee('0 ta post');
    }

    public function test_about_page_does_not_contain_template_placeholder_content(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertDontSee('Tooplate');
        $response->assertDontSee('Clean Work');
        $response->assertDontSee('contact.html');
        $response->assertDontSee('110-220-9800');
        $response->assertDontSee('Lorem ipsum');
        $response->assertDontSee('Trusted by companies');
        $response->assertDontSee('Cleaning Service Agency');
    }
}
