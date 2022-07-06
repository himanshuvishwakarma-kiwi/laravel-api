<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_get_profile_with_valid_token()
    {
        $response   = $this->get('api/profile',[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "user"
            ]);
    }

    public function test_it_return_if_provide_invalid_bearer_token(){
        $this->token = "yJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.dhsd123902kmkfdkfdf";

        $response   = $this->get('api/profile',[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure([
                "message",
                "status"
            ]);
       
    }

    public function test_it_return_if_bearer_token_not_provided(){
        $this->token = " ";

        $response   = $this->get('api/profile',[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                "message",
                "status"
            ]);
    }

    public function test_update_profile_required_validation_errors(){
        $userData = [];
        $userId = 1;
        $userData = [];
        $response = $this->json('POST', 'api/update-profile/'.$userId, $userData,[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                "success" => false,
                "message" => [
                    "first_name" => ["The first name field is required."],
                    "last_name" => ["The last name field is required."],
                    "phone" => ["The phone field is required."],
                ]
            ]);
    }

    public function test_it_return_successful_when_update_user_details(){
        $userData = [];
        $userId = 1;
        $userData = [
            'first_name'=>'John1',
            'last_name'=>'Math',
            'phone'=>'9543955476'
        ];
        $response = $this->json('POST', 'api/update-profile/'.$userId, $userData,[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }
    
    public function test_it_returns_an_error_when_trying_to_update_non_exiting_user_detail(){
        $userData = [];
        $userId = 200000;
        $userData = [
            'first_name'=>'John',
            'last_name'=>'Math',
            'phone'=>'9543955476'
        ];
        $response = $this->json('POST', 'api/update-profile/'.$userId, $userData,[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(404)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_success_when_trying_to_delete_exiting_user(){
        $userId = 9;
        $response = $this->post('api/delete/'.$userId,[],[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_an_error_when_trying_to_delete_non_exiting_user(){
        $userId = 200000;
        $response = $this->post('api/delete/'.$userId,[],[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(404)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_an_unauthorized_error_when_trying_to_get_profile_or_update_profile_or_delete_user_without_logging_in()
    {
        $response = $this->getJson('api/profile', []);

        $response->assertStatus(401);
    }
}
