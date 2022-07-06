<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class GetAllUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_all_users_list(){

        $response = $this->getJson('api/users', [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "success",
                "message",
                "data",
            ]);
    }
    public function test_it_returns_an_unauthorized_error_when_trying_to_get_users_without_logging_in()
    {
        $response = $this->getJson('api/users', []);
        $response->assertStatus(401);
    }
}
