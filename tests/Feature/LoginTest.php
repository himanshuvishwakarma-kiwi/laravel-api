<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function login_required_validation_errors()
    {
        $data = [];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200)
            ->assertJson([
                'success'=>false,
                'message' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function login_invalid_error()
    {
        $data = [
            'email'=>'John.Mathew@yopmail.com',
            'password'=>'12345678'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(400)
            ->assertJson([
                'success'=>false,
                'message' =>"Login credentials are invalid."
            ]);
    }

    public function login_successful()
    {
        $data = [
            'email'=>'John.Mathew@yopmail.com',
            'password'=>'123456789'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200)
            ->assertJson([
                'success',
                'token'
            ]);
    }

}
