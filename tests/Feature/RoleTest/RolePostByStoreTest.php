<?php

namespace Tests\Feature\RoleTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePostByStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_store()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $body = ['name' => fake()->randomElement(['admin','waiter'])];

        $response = $this->actingAs($user, 'sanctum')->postJson('/roles/', $body);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ]
            ]);
    }

    public function test_store_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $body = ['name' => ''];

        $response = $this->actingAs($user, 'sanctum')->postJson('/roles/', $body);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
    }
}
