<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_required_fields_for_registration()
    {
        $userData = [];
        $response = $this->json('POST', 'api/register', $userData,['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "success" => false,
                "message" => [
                    "first_name" => ["The first name field is required."],
                    "last_name" => ["The last name field is required."],
                    "phone" => ["The phone field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    "confirm_password" => ["The confirm password field is required."],
                ]
            ]);
    }
    public function test_confirm_password()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Mathew',
            'phone' => '9999999999',
            'email' => 'John.Mathew@yopmail.com',
            'password' => '12345678',
            'confirm_password' => '123456789'
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "success" => false,
                "message" => [
                    "confirm_password" => ["The confirm password and password must match."]
                ]
            ]);
    }
    public function test_registration_successful()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Mathew',
            'phone' => '9999999999',
            'email' => 'John.Mathew@yopmail.com',
            'password' => '123456789',
            'confirm_password' => '123456789'
        ];
        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                "data",
                "success",
                "message"
            ]);
    }
    public function test_email_already_exist()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Mathew',
            'phone' => '9999999999',
            'email' => 'John.Mathew@yopmail.com',
            'password' => '123456789',
            'confirm_password' => '123456789'
        ];
        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "success" => false,
                "message" => [
                    "email" => ["The email has already been taken."]
                ]
            ]);
    }
}
