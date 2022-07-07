<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PostUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_successful_updating_the_post()
    {
        $postId = 1;
        $data = [
            'title' => 'new title update',
            'description' => 'new description update'
        ];

        $response = $this->postJson("/api/posts/update-post/{$postId}", $data,  [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_field_validation_errors_when_updating_the_post_with_invalid_inputs()
    {
        $postId = 1;
        $data = [
            'title' => '',
            'description' => ''
        ];

        $response = $this->postJson("/api/posts/update-post/{$postId}", $data,  [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_an_unauthorized_error_when_trying_to_update_post_without_logging_in()
    {
        $postId = 1;
        $data = [
            'title' => 'new title',
            'description' => 'new description'
        ];

        $response = $this->postJson("/api/posts/update-post/{$postId}", $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJsonStructure([
            "message",
            "status"
        ]);
    }

}
