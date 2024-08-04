<?php

namespace Tests\Feature;

use App\Models\Autor;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AutorTest extends TestCase
{
    use WithFaker;

    protected string $url = '/api/autor';
    protected string $primaryKey = 'codAu';
    protected array $dataStructure = [
        'codAu',
        'nome',
    ];

    public function test_list_rout_accessible(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get($this->url);

        $response->assertStatus(200);
    }

    public function test_validate_list_data(): void
    {
        $this->factoryListDataCreate();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get($this->url);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->dataStructure
                ]
            ])
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->has('links')
                ->has('meta')
                ->has('data.0', fn(AssertableJson $json) => $json
                    ->whereType('nome', 'string')
                    ->whereType($this->primaryKey, 'integer')
                    ->whereType('livros', 'array')

                )
            );
    }

    protected function factoryListDataCreate($total = 10)
    {
        Autor::factory()->count($total)->create();
    }

    public function test_store_data(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post($this->url, $this->getFakeData());
        $response->assertStatus(201);
    }

    protected function getFakeData()
    {
        return [
            'nome' => $this->faker->firstName()
        ];
    }

    public function test_store_data_validation(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post($this->url, []);
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')
                ->has('errors.nome')
                ->has('message')
            );

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post($this->url, $this->getFakeDataValidation());
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')
                ->has('errors.nome')
                ->has('message')
            );
    }

    protected function getFakeDataValidation()
    {
        return [
            'nome' => $this->faker->realTextBetween(50)
        ];
    }

    public function test_get_data(): void
    {
        $data = $this->factoryDataCreate();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get($this->url.'/'.$data->{$this->primaryKey});

        $arrDataValidation = [];
        foreach ($this->dataStructure as $info) {
            $arrDataValidation[$info] = $data->{$info};
        }

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->dataStructure
            ])
            ->assertJson([
                'data' => $arrDataValidation
            ]);
    }

    protected function factoryDataCreate()
    {
        return Autor::factory()->create();
    }

    public function test_get_data_not_found(): void
    {
        $this->factoryDataCreate();
        $lastId = $this->getLastId();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get($this->url.'/'.$lastId + 1);

        $response->assertStatus(404);
    }

    protected function getLastId()
    {
        $data = Autor::latest()->first();
        return $data->{$this->primaryKey};
    }

    public function test_update_data(): void
    {
        $data = $this->factoryDataCreate();
        $name = $this->faker->text(30);
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put($this->url.'/'.$data->{$this->primaryKey}, [
                'nome' => $name
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    $this->primaryKey => $data->{$this->primaryKey},
                    'nome' => $name,
                ]
            ]);
    }

    public function test_update_data_validation(): void
    {
        $data = $this->factoryDataCreate();

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put($this->url.'/'.$data->{$this->primaryKey}, [
                'nome' => null
            ]);
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')
                ->has('errors.nome')
                ->has('message')
            );


        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put($this->url.'/'.$data->{$this->primaryKey}, $this->getFakeDataValidation());
        $response->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')
                ->has('message'));
    }

    public function test_update_data_not_found(): void
    {
        $this->factoryDataCreate();
        $lastId = $this->getLastId();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put($this->url.'/'.$lastId + 1);

        $response->assertStatus(404);
    }

    public function test_delete_data(): void
    {
        $data = $this->factoryDataCreate();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->delete($this->url.'/'.$data->{$this->primaryKey});

        $response->assertStatus(204);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get($this->url.'/'.$data->{$this->primaryKey});
        $response->assertStatus(404);
    }

    public function test_delete_data_not_found(): void
    {
        $this->factoryDataCreate();
        $lastId = $this->getLastId();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->delete($this->url.'/'.$lastId + 1);
        $response->assertStatus(404);
    }
}
