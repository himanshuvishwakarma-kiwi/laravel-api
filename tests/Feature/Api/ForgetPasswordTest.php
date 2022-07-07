<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ForgetPasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_return_success_on_forget_password()
    {
        $data = [
            'email'=>'John.Mathew@yopmail.com'
        ];
        $response = $this->post('api/forgot-password',$data);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }
    
    public function test_it_returns_field_validation_errors_on_invalid_inputs()
    {
        $data = [];

        $response = $this->postJson('api/forgot-password', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }
    
    public function test_it_returns_errors_when_user_provide_non_register_email()
    {
        $data = [
            'email'=>'example@yopmail.com'
        ];

        $response = $this->postJson('api/forgot-password', $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

}
