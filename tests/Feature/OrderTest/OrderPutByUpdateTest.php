<?php

namespace Tests\Feature\OrderTest;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPutByUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_put()
    {
        $body = [
            'status' => fake()->randomElement(['closed','open', 'canceled', 'paid']),
            'creation_date' => fake()->date(),
            'closing_date' => fake()->date(),
            'user_id' => User::factory()->create()->id,
        ];
        $order = Order::factory()->create();

        $response = $this->put("/orders/{$order->id}/", $body);

        $response->assertStatus(200);
    }
}
