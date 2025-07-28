<?php

namespace Tests\Feature\RoleTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDeleteByDestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_delete()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $role = Role::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson('/roles/'.$role->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ]
            ]);
    }

    public function test_delete_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $id = Role::latest()->first()->id;

        $response = $this->actingAs($user, 'sanctum')->deleteJson('/roles/'.$id+1);

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }
}
