<?php

namespace Tests\Feature\DishTest;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DishPostByUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_update()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $arr = ['.jpeg','.png','.gif'];
        $extension = array_rand($arr);
        $body = [
            'title' => fake()->unique()->title,
            'file' => UploadedFile::fake()->image("test{$arr[$extension]}"),
            'compound' => fake()->text(),
            'calories' => fake()->randomFloat(2, 0, 1000),
            'price' => fake()->randomFloat(2, 0, 100000),
            'category_id' => Category::factory()->create()->id
        ];
        $dish = Dish::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->json('post',"/dishes/$dish->id", $body);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'file_id',
                'compound',
                'calories',
                'price',
                'category_id',
            ]
        ]);
    }

    public function test_update_not_found()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $arr = ['.jpeg','.png','.gif'];
        $extension = array_rand($arr);
        $body = [
            'title' => fake()->unique()->title,
            'file' => UploadedFile::fake()->image("test{$arr[$extension]}", 100, 100),
            'compound' => fake()->text(),
            'calories' => fake()->randomFloat(2, 0, 1000),
            'price' => fake()->randomFloat(2, 0, 100000),
            'category_id' => Category::factory()->create()->id
        ];
        $id = fake()->randomDigit();

        $response = $this->actingAs($user, 'sanctum')->json('post',"/dishes/$id", $body);

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }

    public function test_update_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $dishes = Dish::factory()->create();
        $body = [
            ['title' => $dishes->title],
            ['file' => fake()->word . '.' . fake()->fileExtension('docx')],
            ['compound' => 123],
            ['calories' => fake()->text()],
            ['price' => fake()->text()],
        ];
        $dish = Dish::factory()->create();

        foreach ($body as $value){
            $response = $this->actingAs($user, 'sanctum')->json('post',"/dishes/$dish->id", $value);
            print_r($value);
            $response->assertStatus(422)->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
        }
    }
}
