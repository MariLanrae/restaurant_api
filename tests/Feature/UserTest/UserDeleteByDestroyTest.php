<?php

namespace Tests\Feature\UserTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDeleteByDestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_delete()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $users = User::factory()->for(Role::factory())->create();

        $response = $this->actingAs($user, 'sanctum')->json('delete',"/users/$users->id");

        $response->assertStatus(200);
    }

    public function test_delete_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $id = $user->id+1;

        $response = $this->actingAs($user, 'sanctum')->json('delete',"/users/$id");

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }
}
