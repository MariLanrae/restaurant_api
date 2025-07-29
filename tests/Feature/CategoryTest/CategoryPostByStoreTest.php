<?php

namespace Tests\Feature\CategoryTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CategoryPostByStoreTest extends TestCase
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
        ];

        $response = $this->actingAs($user, 'sanctum')->json('post', '/categories', $body);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'file_id'
                ]
            ]);
    }

    public function test_store_unprocessable_content()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $parameters = [
            ['title' => fake()->unique()->title],
            ['file' => fake()->word . '.' . fake()->fileExtension('docx')],
        ];
        foreach ($parameters as $parameter){
            $response = $this->actingAs($user, 'sanctum')->json('post','/categories', $parameter);
            $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        '*' =>[]
                    ]
                ]);
        }
    }
}
