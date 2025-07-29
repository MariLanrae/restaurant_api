<?php

namespace Tests\Feature\RoleTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleGetByShowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_role_show()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $role = Role::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/roles/'.$role->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ]
            ]);

    }

    public function test_role_show_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $id = fake()->randomDigit();

        $response = $this->actingAs($user, 'sanctum')->getJson('/roles/'.$id);

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);

    }
}
