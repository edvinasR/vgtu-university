<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class LoginControllerTest extends TestCase
{
    public function testRequireEmailAndLogin()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['Email is required!'],
                    'password' => ['Password is required!']
                ]
            ]);

    }

    public function testUserLoginSuccessfully()
    {
        $user = ['email' => 'test@test.com', 'password' => 'test'];
        $this->json('POST', 'api/login', $user)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'accessToken',
                ]
            ]);
    }

    public function testLogoutSuccessfully()
    {
        $user = ['email' => 'test@test.com',
            'password' => 'test'
        ];

        Auth::attempt($user);
        $token = Auth::user()->createToken('nfce_client')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('GET', 'api/logout', [], $headers)
            ->assertStatus(200);
    }
}
