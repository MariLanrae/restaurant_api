<?php

namespace Tests\Feature\UserTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserGetByShowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_user_show()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $users = User::factory()->for(Role::factory())->create();

        $response = $this->actingAs($user, 'sanctum')->json('get',"/users/$users->id");

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'role_id',
                'email',
            ]
        ]);
    }

    public function test_user_show_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $id = fake()->randomDigit();

        $response = $this->actingAs($user, 'sanctum')->json('get',"/users/$id");

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }
}
