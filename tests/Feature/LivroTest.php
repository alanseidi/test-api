<?php

namespace Tests\Feature;

use App\Models\Livro;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use WithFaker;

    protected $url = '/api/livro';

    protected string $primaryKey = 'codL';
    protected array $dataStructure = [
        'codL',
        'titulo',
        'editora',
        'edicao',
        'anoPublicacao',
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
            ->assertJson(fn(AssertableJson $json) => $json->has('data')
                ->has('data.0', fn(AssertableJson $json) => $json
                    ->whereType($this->primaryKey, 'integer')
                    ->whereType('titulo', 'string')
                    ->whereType('editora', 'string')
                    ->whereType('edicao', 'integer')
                    ->whereType('anoPublicacao', 'string')
                    ->whereType('autores', 'array')
                )
            );
    }

    protected function factoryListDataCreate($total = 10)
    {
        Livro::factory()->count($total)->create();
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
            'titulo' => $this->faker->text(30),
            'editora' => $this->faker->text(30),
            'edicao' => $this->faker->numberBetween(100, 9999),
            'anoPublicacao' => $this->faker->year()
        ];
    }

    public function test_store_data_validation(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post($this->url, []);
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('errors')
                ->has('message')
                ->has('errors.titulo')
                ->has('errors.editora')
                ->has('errors.edicao')
                ->has('errors.anoPublicacao')
            );

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post($this->url, $this->getFakeDataValidation());
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('errors')
                ->has('message')
                ->has('errors.titulo')
                ->has('errors.editora')
                ->has('errors.edicao')
                ->has('errors.anoPublicacao')
            );
    }

    protected function getFakeDataValidation()
    {
        return [
            'titulo' => $this->faker->realTextBetween(50),
            'editora' => $this->faker->realTextBetween(50),
            'edicao' => $this->faker->word(),
            'anoPublicacao' => $this->faker->numberBetween(10000, 99999)
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
        return Livro::factory()->create();
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
        $data = Livro::latest()->first();
        return $data->{$this->primaryKey};
    }

    public function test_update_data(): void
    {
        $data = $this->factoryDataCreate();
        $arrDataSave = [
            'titulo' => $this->faker->text(30),
            'editora' => $this->faker->text(30),
            'edicao' => $this->faker->numberBetween(100, 9999),
            'anoPublicacao' => $this->faker->year(),
        ];
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put($this->url.'/'.$data->{$this->primaryKey}, $arrDataSave);

        $arrDataSave[$this->primaryKey] = $data->{$this->primaryKey};
        $response->assertStatus(200)
            ->assertJson([
                'data' => $arrDataSave
            ]);
    }

    public function test_update_data_validation(): void
    {
        $data = $this->factoryDataCreate();

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->put($this->url.'/'.$data->{$this->primaryKey}, [
                'titulo' => null,
                'editora' => null,
                'edicao' => null,
                'anoPublicacao' => null,
            ]);
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('errors')
                ->has('message')
                ->has('errors.titulo')
                ->has('errors.editora')
                ->has('errors.edicao')
                ->has('errors.anoPublicacao')
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
