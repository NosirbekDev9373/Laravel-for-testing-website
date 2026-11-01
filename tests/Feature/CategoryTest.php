<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;

    public function setUp(): void
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
                'name' => 'Category test'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Category test',
        ]);
    }

    public function test_admin_can_category_viewed()
    {
        Category::create([
            'name' => 'Category test'
        ]);

        //admin category sahifaga sirishi
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

        $this->assertDatabaseMissing('categories', ['name' => $category->id]);
    }
}
