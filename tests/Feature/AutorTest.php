<?php

namespace Tests\Feature;

use App\Models\Autor;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AutorTest extends TestCase
{

    public function test_check_autor_api_list_route(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/autor');

        $response->assertStatus(200);
    }

    public function test_check_autor_api_list(): void
    {
        Autor::factory()->count(10)->create();

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/autor');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'codAu',
                        'nome',
                    ]
                ]
            ])
            ->assertJson(fn(AssertableJson $json) => $json->has('data')
                ->has('data.0', fn(AssertableJson $json) => $json->whereType('nome', 'string')
                    ->whereType('codAu', 'integer')
                )
            );
    }

    public function test_store_autor_api(): void
    {
        $faker = Factory::create();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/autor', [
                'nome' => $faker->name()
            ]);
        $response->assertStatus(201);
    }

    public function test_store_autor_api_validation(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/autor', []);
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')
                ->has('errors.nome')
                ->has('message')
            );

        $faker = Factory::create();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/autor', ['name' => $faker->realTextBetween(50)]);
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')
                ->has('errors.nome')
                ->has('message')
            );
    }

    public function test_get_autor_api(): void
    {
        $autor = Autor::factory()->create();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/autor/'.$autor->codAu);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'codAu',
                    'nome',
                ]
            ])
            ->assertJson([
                'data' => [
                    'codAu' => $autor->codAu,
                    'nome' => $autor->nome,
                ]
            ]);
    }

    public function test_get_autor_api_not_found(): void
    {
        Autor::factory()->create();
        $autor = Autor::latest()->first();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/autor/'.$autor->codAu + 1);

        $response->assertStatus(404);
    }

    public function test_update_autor_api(): void
    {
        $autor = Autor::factory()->create();
        $faker = Factory::create();
        $name = $faker->name();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put('/api/autor/'.$autor->codAu, [
                'nome' => $name
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'codAu' => $autor->codAu,
                    'nome' => $name,
                ]
            ]);
    }

    public function test_update_autor_api_validation(): void
    {
        $autor = Autor::factory()->create();
        $faker = Factory::create();

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put('/api/autor/'.$autor->codAu, [
                'nome' => null
            ]);
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')
                ->has('errors.nome')
                ->has('message')
            );


        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put('/api/autor/'.$autor->codAu, [
                'nome' => $faker->realTextBetween(50)
            ]);
        $response->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')
                ->has('message'));
    }

    public function test_put_autor_api_not_found(): void
    {
        Autor::factory()->create();
        $autor = Autor::latest()->first();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put('/api/autor/'.$autor->codAu + 1);

        $response->assertStatus(404);
    }

    public function test_delete_autor_api(): void
    {
        $autor = Autor::factory()->create();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->delete('/api/autor/'.$autor->codAu);

        $response->assertStatus(204);
    }

    public function test_delete_autor_api_not_found(): void
    {
        Autor::factory()->create();
        $autor = Autor::latest()->first();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->delete('/api/autor/'.$autor->codAu + 1);
        $response->assertStatus(404);
    }
}
