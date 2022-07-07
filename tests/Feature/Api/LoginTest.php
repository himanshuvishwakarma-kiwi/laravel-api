<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function test_login_required_validation_errors()
    {
        $data = [];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success'=>false,
                'message' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function test_login_invalid_error()
    {
        $data = [
            'email'=>'John.Mathew@yopmail.com',
            'password'=>'12345678'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'success'=>false,
                'message' =>"Login credentials are invalid."
            ]);
    }

    public function test_login_successful()
    {
        $data = [
            'email'=>'John.Mathew@yopmail.com',
            'password'=>'123456789'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'token'
            ]);
    }
    
    public function test_returns_a_email_not_found_error_when_trying_to_login_with_non_register_email()
    {
        $data = [
            'email'=>'example@yopmail.com',
            'password'=>'123456789'
        ];
        $response = $this->postJson('/api/login', $data);
        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
            'success'=>false,
            'message' =>"Email doesn't found in our database."
        ]);
    }
}
