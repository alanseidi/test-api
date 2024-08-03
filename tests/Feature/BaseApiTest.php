<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BaseApiTest extends TestCase
{

    public function test_base_api_url_is_accessible(): void
    {
        $response = $this->get('/api');
        $response->assertStatus(200);
    }

    public function test_base_api_return_match(): void
    {
        $response = $this->get('/api');
        $response
            ->assertStatus(200)
            ->assertJson([
                'Laravel' => $this->app->version(),
                'API' => env('APP_NAME')
            ]);
    }

}
