<?php

namespace Tests\Feature\DishTest;

use App\Models\Dish;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DishGetByIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_dish_index_paginate()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        Dish::factory()->count(10)->create();

        $response = $this->actingAs($user, 'sanctum')->json('get',"/dishes", [
            'page' => fake()->numberBetween(1,3),
            'perPage' => fake()->numberBetween(1,3),
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'file_id',
                    'compound',
                    'calories',
                    'price',
                    'category_id',
                ]
            ]
        ]);
    }

    public function test_dish_index_with_sort_asc()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        Dish::factory()->count(10)->create();
        $parameters =[
            'sort' => ['title', 'compound', 'price', 'calories'],
        ];
        foreach ($parameters['sort'] as $sort){
            $response = $this->actingAs($user, 'sanctum')->json('get',"/dishes", [
                'page' => 1,
                'perPage' => 5,
                'sort_order' => 'asc',
                'sort' => $sort
            ]);
            $response->assertStatus(200)->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'file_id',
                        'compound',
                        'calories',
                        'price',
                        'category_id',
                    ]
                ]
            ]);
        }
    }

    public function test_dish_index_with_sort_desc()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        Dish::factory()->count(10)->create();
        $parameters =[
            'sort' => ['title', 'compound', 'price', 'calories'],
        ];
        foreach ($parameters['sort'] as $sort){
            $response = $this->actingAs($user, 'sanctum')->json('get',"/dishes", [
                'page' => 1,
                'perPage' => 5,
                'sort_order' => 'desc',
                'sort' => $sort
            ]);

            $response->assertStatus(200)->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'file_id',
                        'compound',
                        'calories',
                        'price',
                        'category_id',
                    ]
                ]
            ]);
        }
    }

    public function test_dish_index_with_search()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $dish = Dish::factory()->count(10)->create()->first();
        $parameters = [
            'title', 'compound',
        ];
        foreach ($parameters as $parameter){
            $response = $this->actingAs($user, 'sanctum')->json('get',"/dishes", [
                'page' => 1,
                'perPage' => 5,
                'search_order' => $dish->$parameter,
                'search' => $parameter
            ]);

            $response->assertStatus(200)->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'file_id'
                    ]
                ]
            ]);
        }
    }

    public function test_dish_index_error_unauthorized()
    {
        $dish = Dish::factory()->count(10)->create()->first();

        $response = $this->json('get',"/dishes", [
            'search' => 'title',
            'search_order' => $dish->title,
            'page' => fake()->numberBetween(1,3),
            'perPage' => fake()->numberBetween(1,3),
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure(['message']);
    }

    public function test_dish_index_error_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $dish = Dish::factory()->count(5)->create();

        $item = ['search','sort_order', 'sort','search_order'];

        $search = ['title', 'compound'];

        $search_order = [$dish->first()->title, $dish->first()->compound];

        $errors = [
            'sort_order' => 'acssc',
            'sort' => 'file',
            'search' => 'help',
            'search_order' => 'helps'
        ];
        foreach ($item as $value) {
            if ($value == 'search_order') {
                foreach ($search as $key) {
                    $data = [
                        'search' => $key,
                        'search_order' => $errors['search_order'],
                    ];
                }
            }
            elseif ($value == 'search') {
                foreach ($search_order as $key){
                    $data =[
                        'search' => $errors['search'],
                        'search_order' => $key,
                    ];
                }
            }
            elseif ($value == ('sort' || 'sort_order')) {
                $data = [
                    $value => $errors[$value]
                ];
            }
            $response = $this->actingAs($user, 'sanctum')->json('get',"/dishes", $data);

            $response->assertStatus(422)->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
        }
    }
}
