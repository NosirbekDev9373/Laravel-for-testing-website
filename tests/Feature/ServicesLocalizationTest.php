<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesLocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create(['name' => 'Tozalash']);

        Post::create([
            'user_id' => User::factory()->create()->id,
            'category_id' => $category->id,
            'title' => 'Xizmat lokalizatsiyasi',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 3,
        ]);
    }

    public function test_services_page_is_translated_to_english(): void
    {
        $this->get(route('locale.change', 'en'));

        $response = $this->get(route('services'));

        $response->assertOk();
        $response->assertSee('All services');
        $response->assertSee('Search');
        $response->assertSee('All');
        $response->assertSee('3 hrs');
        $response->assertSee('Details');
        $response->assertDontSee('Barcha xizmatlar');
    }

    public function test_services_page_is_translated_to_russian(): void
    {
        $this->get(route('locale.change', 'ru'));

        $response = $this->get(route('services'));

        $response->assertOk();
        $response->assertSee('Все услуги');
        $response->assertSee('Поиск');
        $response->assertSee('Все');
        $response->assertSee('3 ч');
        $response->assertSee('Подробнее');
        $response->assertDontSee('Barcha xizmatlar');
    }
}
