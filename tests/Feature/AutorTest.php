<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AutorTest extends TestCase
{

    public function test_check_autor_api_list_route(): void
    {
        $response = $this->get('/autor');

        $response->assertStatus(200);
    }
}
