<?php

namespace Tests\Feature\DishTest;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DishPostByStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_store()
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

        $response = $this->actingAs($user, 'sanctum')->json('post',"/dishes", $body);

        $response->assertStatus(201)->assertJsonStructure([
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

    public function test_store_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $parameters = [
            'title' => fake()->unique()->title,
            'file' => fake()->word . '.' . fake()->fileExtension('docx'),
            'compound' => fake()->text(),
            'calories' => fake()->text(),
            'price' => fake()->text(),
            'category_id' => fake()->randomDigit(),
        ];
        foreach ($parameters as $key => $value){
            $body = [$key => $value];
            $response = $this->actingAs($user, 'sanctum')->json('post', '/categories', $body);
            $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        '*' =>[]
                    ]
                ]);
        }
        $response = $this->actingAs($user, 'sanctum')->json('post', '/categories', $parameters);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]]);
    }
}
