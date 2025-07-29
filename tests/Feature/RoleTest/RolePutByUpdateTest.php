<?php

namespace Tests\Feature\RoleTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePutByUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_update()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $role = Role::factory()->create();

        $body = ['name' => fake()->randomElement(['admin','waiter'])];

        $response = $this->actingAs($user, 'sanctum')->putJson("/roles/".$role->id, $body);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ]
            ]);
    }

    public function test_update_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $id = Role::latest()->first()->id;

        $name = [fake()->randomElement(['admin','super_admin','waiter'])];

        $response = $this->actingAs($user, 'sanctum')->putJson("/roles/".$id+1, $name);

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }

    public function test_update_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $role = Role::factory()->create();

        $body = ['name' => 'super_admin'];

        $response = $this->actingAs($user, 'sanctum')->putJson("/roles/".$role->id, $body);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
    }
}
