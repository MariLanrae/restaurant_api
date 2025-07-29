<?php

namespace Database\Factories;

use App\Models\Dish;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;
    public function definition(): array
    {
        return [
           'user_id' => User::factory()->create()->id,
            'status' => fake()->randomElement(['closed','open', 'canceled', 'paid']),
            'number' => fake()->unique()->text(10),
            'creation_date' => fake()->dateTime(),
            'closing_date' => fake()->dateTime(),
        ];
    }
}
