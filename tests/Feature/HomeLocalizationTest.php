<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_translated_to_english(): void
    {
        $this->get(route('locale.change', 'en'));

        $response = $this->get(route('main'));

        $response->assertOk();
        $response->assertSee('lang="en"', false);
        $response->assertSee('Find on our platform:');
        $response->assertSee('View posts');
        $response->assertSee('Reliable and convenient');
        $response->assertSee('Best offers');
        $response->assertDontSee('Platformamizda toping:');
    }

    public function test_home_page_is_translated_to_russian(): void
    {
        $this->get(route('locale.change', 'ru'));

        $response = $this->get(route('main'));

        $response->assertOk();
        $response->assertSee('lang="ru"', false);
        $response->assertSee('Найдите на нашей платформе:');
        $response->assertSee('Смотреть посты');
        $response->assertSee('Надёжная и удобная');
        $response->assertSee('Лучшие предложения');
        $response->assertDontSee('Platformamizda toping:');
    }

    public function test_home_page_stays_uzbek_by_default(): void
    {
        $response = $this->get(route('main'));

        $response->assertOk();
        $response->assertSee('lang="uz"', false);
        $response->assertSee('Platformamizda toping:');
        $response->assertSee('Eng yaxshi takliflar');
    }
}
