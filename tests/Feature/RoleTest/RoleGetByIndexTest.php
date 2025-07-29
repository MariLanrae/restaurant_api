<?php

namespace Tests\Feature\RoleTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleGetByIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_role_index_paginate()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();

        $parameters = [
            'page' => fake()->randomDigit(),
            'perPage' => fake()->randomDigit(),
        ];
        $data = http_build_query($parameters);
        $response = $this->actingAs($user, 'sanctum')->getJson('/roles?'. $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ]
                ]
            ]);
    }

    public function test_role_index_error_unauthorized()
    {
        Role::factory()->count(3)->create();
        $parameters = [
            'page' => fake()->randomDigit(),
            'perPage' => fake()->randomDigit(),
        ];
        $data = http_build_query($parameters);
        $response = $this->getJson('/roles?'. $data);

        $response->assertStatus(401)
            ->assertJsonStructure(['message']);
    }

    public function test_role_index_error_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        Role::factory()->count(3)->create();
        $parameters = [
            'page' => fake()->randomDigit(),
        ];
        $data = http_build_query($parameters);
        $response = $this->actingAs($user, 'sanctum')->getJson('/roles?'. $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
    }
}
