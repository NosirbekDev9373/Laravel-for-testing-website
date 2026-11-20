<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->category = Category::create([
            'name' => 'Tozalash xizmatlari',
        ]);
    }

    private function createPost(array $overrides = []): Post
    {
        return Post::create(array_merge([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Servis posti',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 150,
            'duration' => 3,
        ], $overrides));
    }

    public function test_services_page_shows_real_posts_and_links(): void
    {
        $post = $this->createPost(['title' => 'Haqiqiy xizmat posti']);

        $response = $this->get(route('services'));

        $response->assertOk();
        $response->assertSee('Barcha xizmatlar');
        $response->assertSee('Haqiqiy xizmat posti');
        $response->assertSee($this->category->name);
        $response->assertSee(route('posts.show', $post), false);
        $response->assertSee(route('services', ['category' => $this->category->id]), false);
        $response->assertViewHas('posts', fn ($posts) => $posts->count() === 1);
        $response->assertDontSee('servicesDetail.blade.php');
        $response->assertDontSee('services-detail.html');
        $response->assertDontSee('Office Cleaning');
        $response->assertDontSee('Best Cleaning Service Provider');
        $response->assertDontSee('Learn More');
        $response->assertDontSee('Trusted by companies');
        $response->assertDontSee('$820');
    }

    public function test_services_page_filters_by_category(): void
    {
        $otherCategory = Category::create(['name' => 'Boshqa kategoriya']);

        $visiblePost = $this->createPost(['title' => 'Ko‘rinadigan post']);
        $hiddenPost = $this->createPost([
            'title' => 'Yashirin post',
            'category_id' => $otherCategory->id,
        ]);

        $response = $this->get(route('services', ['category' => $this->category->id]));

        $response->assertOk();
        $response->assertSee($visiblePost->title);
        $response->assertDontSee($hiddenPost->title);
        $response->assertViewHas('posts', fn ($posts) => $posts->count() === 1);
    }

    public function test_services_page_searches_by_title_and_content(): void
    {
        $this->createPost([
            'title' => 'Noyob xizmat',
            'short_content' => 'Maxsus qisqa matn',
        ]);
        $this->createPost(['title' => 'Boshqa xizmat']);

        $response = $this->get(route('services', ['search' => 'noyob']));

        $response->assertOk();
        $response->assertSee('Noyob xizmat');
        $response->assertDontSee('Boshqa xizmat');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);

        $response = $this->get(route('services', ['search' => 'maxsus qisqa']));

        $response->assertSee('Noyob xizmat');
        $response->assertDontSee('Boshqa xizmat');
    }

    public function test_services_page_search_by_category_name(): void
    {
        $this->createPost(['title' => 'Tozalash xizmati posti']);

        $response = $this->get(route('services', ['search' => 'tozalash xizmatlari']));

        $response->assertOk();
        $response->assertSee('Tozalash xizmati posti');
    }

    public function test_services_page_search_can_be_combined_with_category(): void
    {
        $otherCategory = Category::create(['name' => 'Boshqa kategoriya']);

        $this->createPost(['title' => 'Mos xizmat']);
        $this->createPost([
            'title' => 'Mos boshqa xizmat',
            'category_id' => $otherCategory->id,
        ]);

        $response = $this->get(route('services', [
            'category' => $this->category->id,
            'search' => 'mos',
        ]));

        $response->assertOk();
        $response->assertSee('Mos xizmat');
        $response->assertDontSee('Mos boshqa xizmat');
        $response->assertViewHas('posts', fn ($posts) => $posts->total() === 1);
    }

    public function test_services_page_search_shows_empty_state(): void
    {
        $this->createPost(['title' => 'Oddiy xizmat']);

        $response = $this->get(route('services', ['search' => 'topilmadi']));

        $response->assertOk();
        $response->assertSee('Qidiruv bo‘yicha xizmat topilmadi.');
        $response->assertDontSee('Oddiy xizmat');
    }

    public function test_services_page_rejects_invalid_category(): void
    {
        $this->get(route('services', ['category' => 999999]))->assertNotFound();
        $this->get(route('services', ['category' => 'not-a-number']))->assertNotFound();
    }

    public function test_services_page_shows_empty_state_without_posts(): void
    {
        $response = $this->get(route('services'));

        $response->assertOk();
        $response->assertSee('Hozircha xizmatlar yo‘q.');
    }

    public function test_services_page_paginates_posts(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->createPost(['title' => 'Servis posti '.$i]);
        }

        $firstPage = $this->get(route('services'));

        $firstPage->assertOk();
        $firstPage->assertViewHas('posts', fn ($posts) => $posts->count() === 6 && $posts->total() === 7);

        $secondPage = $this->get(route('services', ['page' => 2]));

        $secondPage->assertOk();
        $secondPage->assertViewHas('posts', fn ($posts) => $posts->count() === 1);
    }

    public function test_services_page_is_not_rate_limited(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->get(route('services'))->assertOk();
        }
    }

    public function test_legacy_services_detail_url_redirects_to_real_pages(): void
    {
        $post = $this->createPost(['title' => 'Legacy servis posti']);

        $this->get(route('servicesDetail'))
            ->assertStatus(301)
            ->assertRedirect(route('services'));

        $this->get(route('servicesDetail.show', $post))
            ->assertStatus(301)
            ->assertRedirect(route('posts.show', $post));

        $this->get(route('servicesDetail.show', 999999))->assertNotFound();
    }
}
