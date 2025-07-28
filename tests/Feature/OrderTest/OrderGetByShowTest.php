<?php

namespace Tests\Feature\OrderTest;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderGetByShowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_show()
    {
        $order = Order::factory()->create();

        $response = $this->get('/orders/'.$order->id);

        $response->assertStatus(200);
    }
}
