<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_login()
    {
        $parametrs = [
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'pincode' => fake()->unique()->asciify('****'),
        ];

        User::factory()->create($parametrs);

        $response = $this->post('/login', $parametrs);

        $response->assertStatus(200);
    }
    public function test_logout()
    {

    }
}
