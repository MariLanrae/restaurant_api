<?php

namespace Tests\Feature\DishTest;

use App\Models\Dish;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DishGetByShowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_dish_show()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $dish = Dish::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->json('get',"/dishes/$dish->id");

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'file_id',
                'compound',
                'calories',
                'price',
                'category_id',
            ]
        ]);
    }

    public function test_dish_show_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $id = fake()->randomDigit();

        $response = $this->actingAs($user, 'sanctum')->json('get',"/dishes/$id");

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }
}
