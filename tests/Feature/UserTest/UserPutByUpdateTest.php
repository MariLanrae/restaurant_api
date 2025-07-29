<?php

namespace Tests\Feature\UserTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPutByUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_update()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $users = User::factory()->for(Role::factory())->create();

        $body = [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role_id' => Role::factory()->create()->id,
        ];

        $response = $this->actingAs($user, 'sanctum')->json('put',"/users/$users->id", $body);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'role_id',
                'email',
            ]
        ]);
    }

    public function test_update_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $body = [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role_id' => Role::factory()->create()->id,
        ];
        $id = $user->id+1;

        $response = $this->actingAs($user, 'sanctum')->json('put',"/users/$id", $body);

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }

    public function test_update_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $body = [
            ['email' => $user->email],
            ['role_id' => (Role::latest()->first()->id)+10],
        ];
        $users = User::factory()->for(Role::factory())->create();

        foreach ($body as $value){
            $response = $this->actingAs($user, 'sanctum')->json('put',"/users/$users->id", $value);
            $response->assertStatus(422)->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
        }
    }
}
