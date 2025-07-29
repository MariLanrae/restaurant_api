<?php

namespace Tests\Feature\CategoryTest;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryDeleteByDestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_delete()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->json('delete',"/categories/$category->id");

        $response->assertStatus(200);
    }

    public function test_delete_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $id = fake()->randomDigit();

        $response = $this->actingAs($user, 'sanctum')->json('delete',"/categories/$id");

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }
}
