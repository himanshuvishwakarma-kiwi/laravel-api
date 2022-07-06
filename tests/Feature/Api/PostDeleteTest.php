<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PostDeleteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_successful_delete_the_post()
    {
        $postId = 1;
        $data = [
            'id' => $postId,
        ];

        $response = $this->postJson("/api/posts/remove-post/{$postId}", $data,  [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_errors_when_deleting_non_existing_post()
    {
        $postId = 21;
        $data = [
            'id' => $postId,
        ];

        $response = $this->postJson("/api/posts/remove-post/{$postId}", $data,  [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
        ->assertJsonStructure([
            "success",
            "message"
        ]);
    }

    public function test_it_returns_an_unauthorized_error_when_trying_to_delete_post_without_logging_in()
    {
        $postId = 1;
        $data = [
            'id' => $postId,
        ];

        $response = $this->postJson("/api/posts/remove-post/{$postId}", $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJsonStructure([
            "message",
            "status"
        ]);
    }
}
