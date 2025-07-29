<?php

namespace Tests\Feature\CategoryTest;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryGetByIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_category_index_paginate()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        Category::factory()->count(10)->create();

        $response = $this->actingAs($user, 'sanctum')->json('get', '/categories', [
            'page' => fake()->numberBetween(1,3),
            'perPage' => fake()->numberBetween(1,3),
        ] );
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'file_id'
                    ]
                ]
            ]);
    }

    public function test_category_index_with_sort()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        Category::factory()->count(10)->create();
        $parameters = ['asc', 'desc',];
        foreach ($parameters as $parameter){
            $response = $this->actingAs($user, 'sanctum')->json('get', '/categories', [
                'sort' => 'title',
                'sort_order' => $parameter,
                'page' => 1,
                'perPage' => 5,
            ] );
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

    public function test_category_index_with_search()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $category = Category::factory()->count(3)->create()->first();

        $response = $this->actingAs($user, 'sanctum')->json('get', '/categories', [
            'search' => 'title',
            'search_order' => $category->title,
            'page' => 1,
            'perPage' => 5,
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

    public function test_category_index_error_unauthorized()
    {
        $category = Category::factory()->count(3)->create()->first();
        $response = $this->json('get', '/categories', [
            'search' => 'title',
            'search_order' => $category->title,
            'page' => 1,
            'perPage' => 5,
        ]);

        $response->assertStatus(401)->assertJsonStructure(['message']);
    }

    public function test_category_index_error_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        Category::factory()->count(3)->create()->first();
        $parameters = [
            'search' => 'title',
            'sort_order' => 'desc',
            'sort' => 'title',
            'search_order' => Category::latest()->first()->title,
            'page' => 1,
            'perPage' => 5,
        ];
        $errors = [
            'search' => ' 1',
            'sort_order' => 'acssc',
            'sort' => 'file',
            'search_order' => '123',
        ];

        $item = ['search','sort_order', 'sort','search_order'];

        foreach ($item as $val ){
            $parameter = $parameters;
            $parameter[$val] = $errors[$val];

            $response = $this->actingAs($user, 'sanctum')->json('get', '/categories', $parameter);

            $response->assertStatus(422)->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
        }
    }
}
