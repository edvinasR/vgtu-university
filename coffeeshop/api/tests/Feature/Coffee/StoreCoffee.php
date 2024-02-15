<?php

namespace Tests\Feature\Coffee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreCoffee extends TestCase
{
    use AuthHeaders;

    public function testEmptyPostData()
    {
        $this->json('POST', 'api/coffee', [], $this -> getHeaders())
        ->assertStatus(422);
    }
}
