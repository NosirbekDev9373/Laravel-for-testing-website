<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
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

    public function test_admin_can_create_tag(): void
    {
        $response = $this->actingAs($this->user)
        ->post(route('admin.tags.store'), [
            'name' => 'test',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tags', [
            'name' => 'test',
        ]);

    }

    public function test_admin_can_tag_viewed(): void
    {
        Tag::create([
            'name' => 'test',
        ]);
        //admin teg sahifaga kirish
        $response = $this->actingAs($this->user)->get(route('admin.tags.index'));

        $response->assertOk();
        //bazada borligini tesshiradi
        $response->assertSee('test');
    }
    public function test_admin_can_delete_tag(): void
    {
        $tag = Tag::create([
            'name' => 'Delete me',
        ]);
        $response = $this->actingAs($this->user)->delete(route('admin.tags.destroy', $tag));
        $response->assertRedirect();

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }
}
