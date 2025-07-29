<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Dish;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Dish::class;

    public function definition(): array
    {
        return [
            'title'=> fake()->unique()->text(10),
            'file_id' => File::factory()->create()->id,
            'compound' => fake()->text(),
            'calories' => fake()->randomFloat(2, 0, 1000),
            'price' => fake()->randomFloat(2, 0, 100000),
            'category_id' => Category::factory()->create()->id
        ];
    }
}
