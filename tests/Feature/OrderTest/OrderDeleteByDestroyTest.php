<?php

namespace Tests\Feature\OrderTest;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderDeleteByDestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_delete()
    {
        $order = Order::factory()->create();

        $response = $this->delete('/orders/'.$order->id);

        $response->assertStatus(200);
    }
}
