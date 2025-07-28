<?php

namespace Tests\Feature\UserTest;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPostByStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_store()
    {
        $user = User::factory()->for(Role::factory(['name' => 'super_admin']))->create()->first();
        $body = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'pincode' => fake()->unique()->asciify('****'),
            'role_id' => Role::factory()->create()->id,
        ];

        $response = $this->actingAs($user, 'sanctum')->json('post','/users', $body);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'role_id',
                'email',
            ]
        ]);
    }

    public function test_store_unprocessable_content()
    {
        Artisan::call('db:seed');
        $user = User::latest()->first();
        $parameters = [
            ['name' => fake()->name()],
            ['email' => $user->email],
            ['password' => Hash::make('password')],
            ['pincode' => fake()->unique()->asciify('*')],
            ['role_id' => 172],
        ];
        foreach ($parameters as $parameter){
            $response = $this->actingAs($user, 'sanctum')->json('post','/users', $parameter);
            $response->assertStatus(422)->assertJsonStructure([
                'message',
                'errors' => [
                    '*' =>[]
                ]
            ]);
        }
    }
}
