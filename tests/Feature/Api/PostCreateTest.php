<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PostCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_the_post_on_successfully_creating_a_new_post()
    {
        dd($this->token);
        $data = [
            'title' => 'test title',
            'description' => 'test description'
        ];

        $response = $this->postJson('/api/posts/new-post', $data, [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);
        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }
    
    public function test_it_returns_field_validation_errors_when_creating_a_new_post_with_invalid_inputs()
    {
        $data = [
            'title' => '',
            'description' => ''
        ];

        $response = $this->postJson('/api/posts/new-post', $data,  [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_an_unauthorized_error_when_trying_to_create_post_without_logging_in()
    {
        $response = $this->postJson('/api/posts/new-post', []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJsonStructure([
            "message",
            "status"
        ]);
    }
}
