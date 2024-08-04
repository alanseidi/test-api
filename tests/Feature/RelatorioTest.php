<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RelatorioTest extends TestCase
{

    public function test_relatorio_route(): void
    {
        $response = $this->get('/api/relatorio');

        $response->assertStatus(200);
    }
}
