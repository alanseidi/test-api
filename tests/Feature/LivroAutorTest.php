<?php

namespace Tests\Feature;

use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LivroAutorTest extends TestCase
{
    use WithFaker;

    public function test_associate_autores_to_livro(): void
    {
        $autores = Autor::factory()->count(3)->create();
        $livro = Livro::factory()->create();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/livro/associar-autor', [
                'codL' => $livro->codL,
                'arrayCodAu' => $autores->pluck('codAu')->toArray()
            ]);

   
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'codL' => $livro->codL,
                    'titulo' => $livro->titulo,
                    'editora' => $livro->editora,
                    'edicao' => $livro->edicao,
                    'anoPublicacao' => $livro->anoPublicacao,
                    'autores' => array_map(function ($autor) {
                        return [
                            'codAu' => $autor['codAu'],
                            'nome' => $autor['nome'],
                        ];
                    }, $autores->toArray()),
                ]
            ]);
    }
}