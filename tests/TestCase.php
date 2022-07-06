<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        
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
        $responseData = json_decode($response->getContent());
        $this->assertTrue(isset($responseData->token));
        $this->token = $responseData->token;
    }
}
