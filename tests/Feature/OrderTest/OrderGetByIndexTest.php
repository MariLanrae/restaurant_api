<?php

namespace Tests\Feature\OrderTest;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class OrderGetByIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_index()
    {
        Artisan::call('db:seed');
        $user = User::latest()->first();
        Order::factory()->count(1)->create();
        $parameters = [
            'page' => fake()->numberBetween(1,3),
            'perPage' => fake()->numberBetween(1,3),
        ];
        $data = http_build_query($parameters);

        $response = $this->actingAs($user, 'sanctum')->getJson("/orders?". $data);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'number',
                    'closing_date',
                    'creation_date',
                    'status',
                    'user_id',
                ]
            ]
        ]);
    }
}
