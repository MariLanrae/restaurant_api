<?php

namespace Tests\Feature\UserTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserGetByIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_user_index_paginate()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        User::factory()->for(Role::factory())->count(10)->create();
        $response = $this->actingAs($user, 'sanctum')->json('get',"/users", [
            'page' => fake()->numberBetween(1,3),
            'perPage' => fake()->numberBetween(1,10),
        ]);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'role_id',
                    'email',
                ]
            ]
        ]);
    }

    public function test_user_index_with_sort_asc()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        User::factory()->for(Role::factory())->count(10)->create();
        $parameters = ['name','role_id'];
        foreach ($parameters as $parameter){
            $response = $this->actingAs($user, 'sanctum')->json('get', "/users", [
                'sort_order' => 'asc',
                'sort' => $parameter,
                'page' => fake()->numberBetween(1,3),
                'perPage' => fake()->numberBetween(1,3),
            ]);
            $response->assertStatus(200)->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'role_id',
                        'email',
                    ]
                ]
            ]);
        }
    }

    public function test_user_index_with_sort_desc()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        User::factory()->for(Role::factory())->count(10)->create();
        $parameters = ['name','role_id'];
        foreach ($parameters as $parameter){
            $response = $this->actingAs($user, 'sanctum')->json('get', "/users", [
                'sort_order' => 'desc',
                'sort' => $parameter,
                'page' => fake()->numberBetween(1,3),
                'perPage' => fake()->numberBetween(1,3),
            ]);
            $response->assertStatus(200)->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'role_id',
                        'email',
                    ]
                ]
            ]);
        }
    }

    public function test_user_index_with_search()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $users = User::factory()->for(Role::factory())->count(10)->create()->first();
        $parameters = ['name','role_id',];
        foreach ($parameters as $parameter){
            $response = $this->actingAs($user, 'sanctum')->json('get',"/users", [
                'page' => 1,
                'perPage' => 5,
                'search_order' => strval($users->$parameter),
                'search' => $parameter,
            ]);
            $response->assertStatus(200)->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'role_id',
                        'email',
                    ]
                ]
            ]);
        }
    }

    public function test_user_index_error_unauthorized()
    {
        User::factory()->for(Role::factory())->count(3)->create()->first();

        $response = $this->json('get',"/users", [
            'page' => fake()->numberBetween(1,3),
            'perPage' => fake()->numberBetween(1,10),
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure(['message']);
    }

    public function test_user_index_error_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        User::factory()->for(Role::factory())->count(3)->create()->first();
        $parameters = [
            [
                'search' => 'role_id',
                'search_order' => $user->role_id,
                'sort' => 'role_id',
                'sort_order' => 'desc',
                'page' => 1,
                'perPage' => 5
            ],
            [
                'search' => 'name',
                'search_order' => $user->name,
                'sort' => 'name',
                'sort_order' => 'asc',
                'page' => 1,
                'perPage' => 5
            ]
        ];
        $errors = [
            'search' => 'fail',
            'search_order' => '123',
            'sort' =>  'hope',
            'sort_order' => 'inval',
        ];

        $item = ['search','search_order', 'sort', 'sort_order'];

        foreach ($parameters as $parameter) {
            foreach ($item as $key) {
                $params = $parameter;
                $params[$key] = $errors[$key];
                $response = $this->actingAs($user, 'sanctum')->json('get',"/users", $params);
                $response->assertStatus(422)->assertJsonStructure([
                    'message',
                    'errors' => [
                        '*' => []
                    ]
                ]);
            }
        }
    }
}
