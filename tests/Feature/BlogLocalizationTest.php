<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogLocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create(['name' => 'Tozalash']);

        Post::create([
            'user_id' => User::factory()->create()->id,
            'category_id' => $category->id,
            'title' => 'Blog lokalizatsiyasi',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 3,
        ]);
    }

    public function test_blog_page_is_translated_to_english(): void
    {
        $this->get(route('locale.change', 'en'));

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee('Search');
        $response->assertSee('All');
        $response->assertSee('3 hrs');
        $response->assertSee('Read');
        $response->assertSee('More details');
        $response->assertDontSee('Qidiruv');
    }

    public function test_blog_page_is_translated_to_russian(): void
    {
        $this->get(route('locale.change', 'ru'));

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee('Блог');
        $response->assertSee('Поиск');
        $response->assertSee('Все');
        $response->assertSee('3 ч');
        $response->assertSee('Читать');
        $response->assertSee('Подробнее');
        $response->assertDontSee('Qidiruv');
    }
}
