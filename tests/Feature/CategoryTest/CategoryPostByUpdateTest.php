<?php

namespace Tests\Feature\CategoryTest;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CategoryPostByUpdateTest extends TestCase
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
            'file' => UploadedFile::fake()->image("test{$arr[$extension]}", 100, 100),
        ];
        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->json('post',"/categories/$category->id", $body);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'file_id'
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
        ];
        $id = fake()->randomDigit();

        $response = $this->actingAs($user, 'sanctum')->postJson("/categories/$id", $body);

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }

    public function test_update_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $categories = Category::factory()->create();
        $body = [
            ['title' => $categories->title],
            ['file' => fake()->word . '.' . fake()->fileExtension('docx')],
        ];
        $category = Category::factory()->create();

        foreach ($body as $value){
            $response = $this->actingAs($user, 'sanctum')->json('post',"/categories/$category->id", $value);
            $response->assertStatus(422)->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
        }
    }
}
