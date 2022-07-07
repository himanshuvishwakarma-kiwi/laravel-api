<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_success_on_reset_password()
    {
        $data = [
            'password'=>"123456789",
            'password_confirmation'=>'123456789'
        ];
        $resetPwdToken = "wE23BC4FE2PTxzK9CbOmIhBLGbhg8DV0Vnc5FCKuNbkGWhPI8uWEVEKz6iK0ObiD";
        $response = $this->postJson('api/reset-password/'.$resetPwdToken, $data);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }
    
    public function test_it_returns_field_validation_errors_on_invalid_inputs()
    {
        $data = [];
        $resetPwdToken = "wE23BC4FE2PTxzK9CbOmIhBLGbhg8DV0Vnc5FCKuNbkGWhPI8uWEVEKz6iK0ObiD";
        $response = $this->postJson('api/reset-password/'.$resetPwdToken, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_when_password_and_password_confirmation_not_match()
    {
        $data = [
            'password'=>"123456789",
            'password_confirmation'=>'12345679'
        ];
        $resetPwdToken = "wE23BC4FE2PTxzK9CbOmIhBLGbhg8DV0Vnc5FCKuNbkGWhPI8uWEVEKz6iK0ObiD";
        $response = $this->postJson('api/reset-password/'.$resetPwdToken, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }
    
    public function test_it_returns_reset_password_token_error()
    {
        $data = [
            'password'=>"123456789",
            'password_confirmation'=>'123456789'
        ];
        $resetPwdToken = "wE23BC4FE2PTxzK9CbOmIhBLGbhg8DV0Vnc5FCKuNbkGWhPI8uWEVEKz6iK0ObiD4324";
        $response = $this->postJson('api/reset-password/'.$resetPwdToken, $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }
}
