<?php

namespace Tests\Feature;

use App\Models\Assunto;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AssuntoTest extends TestCase
{
    use WithFaker;

    protected $url = '/api/assunto';

    protected string $primaryKey = 'codAs';
    protected array $dataStructure = [
        'codAs',
        'descricao',
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
                    ->whereType($this->primaryKey, 'integer')
                    ->whereType('descricao', 'string')
                    ->whereType('livros', 'array')
                )
            );
    }

    protected function factoryListDataCreate($total = 10)
    {
        Assunto::factory()->count($total)->create();
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
            'descricao' => $this->faker->text(20),
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
                ->has('errors.descricao')
            );

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post($this->url, $this->getFakeDataValidation());
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('errors')
                ->has('message')
                ->has('errors.descricao')
            );
    }

    protected function getFakeDataValidation()
    {
        return [
            'descricao' => $this->faker->realTextBetween(50),
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
        return Assunto::factory()->create();
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
        $data = Assunto::latest()->first();
        return $data->{$this->primaryKey};
    }

    public function test_update_data(): void
    {
        $data = $this->factoryDataCreate();
        $arrDataSave = [
            'descricao' => $this->faker->text(20),
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
                'descricao' => null,
            ]);
        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('errors')
                ->has('message')
                ->has('errors.descricao')
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
