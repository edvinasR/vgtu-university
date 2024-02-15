<?php

namespace Tests\Feature\Coffee;
use Illuminate\Support\Facades\Auth;

trait AuthHeaders
{
    public function getHeaders(){
        $user = [
            'email' => 'test@test.com',
            'password' => 'test'
        ];
        Auth::attempt([
            'email' => 'test@test.com',
            'password' => 'test'
        ]);
        $token = Auth::user()->createToken('client')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];
        return $headers;
    }
}
