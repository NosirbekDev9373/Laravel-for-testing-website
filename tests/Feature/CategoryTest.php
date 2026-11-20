<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $role = Role::create([
            'name' => 'admin',
        ]);
        $this->user->roles()->attach($role->id);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.categories.store'), [
                'name' => 'Category test',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Category Test',
        ]);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::create(['name' => 'Eski nom']);

        $this->actingAs($this->user)
            ->put(route('admin.categories.update', $category), [
                'name' => 'yangi nom',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Yangi Nom',
        ]);
    }

    public function test_category_name_must_be_unique_case_insensitively(): void
    {
        Category::create(['name' => 'Tozalash']);

        $this->actingAs($this->user)
            ->post(route('admin.categories.store'), [
                'name' => 'tozalash',
            ])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('categories', 1);
    }

    public function test_admin_can_search_categories(): void
    {
        Category::create(['name' => 'Tozalash']);
        Category::create(['name' => 'Remont']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.categories.index', ['search' => 'tozal']));

        $response->assertOk();
        $response->assertSee('Tozalash');
        $response->assertDontSee('Remont');
    }

    public function test_category_with_posts_cannot_be_deleted(): void
    {
        $category = Category::create(['name' => 'Postli kategoriya']);

        Post::create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => 'Kategoriyadagi post',
            'short_content' => 'Qisqa mazmun',
            'content' => 'To‘liq mazmun',
            'price' => 100,
            'duration' => 1,
        ]);

        $this->actingAs($this->user)
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_category_form_shows_validation_errors(): void
    {
        $response = $this->actingAs($this->user)
            ->followingRedirects()
            ->from(route('admin.categories.index'))
            ->post(route('admin.categories.store'), []);

        $response->assertSee('Formada xatolar bor');
    }

    public function test_admin_can_category_viewed()
    {
        Category::create([
            'name' => 'Category test',
        ]);

        // admin category sahifaga sirishi
        $response = $this->actingAs($this->user)->get(route('admin.categories.index'));
        $response->assertOk();
        $response->assertSee('Category test');
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::create([
            'name' => 'Category delete',
        ]);
        $response = $this->actingAs($this->user)->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_update_tag(): void
    {
        $tag = Tag::create(['name' => 'Eski teg']);

        $this->actingAs($this->user)
            ->put(route('admin.tags.update', $tag), [
                'name' => 'yangi teg',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'Yangi Teg',
        ]);
    }

    public function test_admin_can_search_tags(): void
    {
        Tag::create(['name' => 'Yuvish']);
        Tag::create(['name' => 'Remont']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.tags.index', ['search' => 'remont']));

        $response->assertOk();
        $response->assertSee('Remont');
        $response->assertDontSee('Yuvish');
    }
}
