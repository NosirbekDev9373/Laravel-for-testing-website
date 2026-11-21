<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutLocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $author = User::factory()->create(['name' => 'Muallif Test']);
        $author->roles()->attach(Role::firstOrCreate(['name' => 'editor'])->id);

        $category = Category::create(['name' => 'Tozalash']);

        Post::create([
            'user_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Lokalizatsiya posti',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 2,
        ]);
    }

    public function test_about_page_is_translated_to_english(): void
    {
        $this->get(route('locale.change', 'en'));

        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('About Us');
        $response->assertSee('Our authors');
        $response->assertSee('Platform statistics');
        $response->assertSee('Editor');
        $response->assertSee('Users');
        $response->assertSee('Categories');
        $response->assertDontSee('Bizning mualliflar');
    }

    public function test_about_page_is_translated_to_russian(): void
    {
        $this->get(route('locale.change', 'ru'));

        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('О нас');
        $response->assertSee('Наши авторы');
        $response->assertSee('Статистика платформы');
        $response->assertSee('Редактор');
        $response->assertSee('Пользователи');
        $response->assertSee('Категории');
        $response->assertDontSee('Bizning mualliflar');
    }
}
