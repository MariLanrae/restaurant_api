<?php

namespace Tests\Feature\OrderTest;

use App\Models\Dish;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPostByStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_post()
    {
        $body = [
            'number' => fake()->title,
            'user_id' => User::factory()->create()->id,
            'status' => fake()->randomElement(['closed','open', 'canceled', 'paid']),
            'dishes' => [[
                'id' => Dish::factory()->create()->id,
                'quantity' => fake()->randomDigit()]
            ],
        ];

        $response = $this->post('/orders/', $body);

        $response->assertStatus(201);
    }
}
