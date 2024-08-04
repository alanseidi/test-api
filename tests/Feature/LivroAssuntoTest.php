<?php


use App\Models\Assunto;
use App\Models\Livro;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LivroAssuntoTest extends TestCase
{
    use WithFaker;

    public function test_associate_assuntos_to_livro(): void
    {
        $assuntos = Assunto::factory()->count(3)->create();
        $livro = Livro::factory()->create();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/livro/associar-assunto', [
                'codL' => $livro->codL,
                'arrayCodAs' => $assuntos->pluck('codAs')->toArray()
            ]);


        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'codL' => $livro->codL,
                    'titulo' => $livro->titulo,
                    'editora' => $livro->editora,
                    'edicao' => $livro->edicao,
                    'anoPublicacao' => $livro->anoPublicacao,
                    'preco' => $livro->preco,
                    'autores' => [],
                    'assuntos' => array_map(function ($assunto) {
                        return [
                            'codAs' => $assunto['codAs'],
                            'descricao' => $assunto['descricao'],
                        ];
                    }, $assuntos->toArray()),
                ]
            ]);
    }

    public function test_associate_livros_to_assunto(): void
    {
        $assunto = Assunto::factory()->create();
        $livros = Livro::factory()->count(5)->create();
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/assunto/associar-livro', [
                'codAs' => $assunto->codAs,
                'arrayCodL' => $livros->pluck('codL')->toArray()
            ]);


        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'codAs' => $assunto->codAs,
                    'descricao' => $assunto->descricao,
                    'livros' => array_map(function ($livro) {
                        return [
                            'codL' => $livro['codL'],
                            'titulo' => $livro['titulo'],
                            'editora' => $livro['editora'],
                            'edicao' => $livro['edicao'],
                            'anoPublicacao' => $livro['anoPublicacao'],
                            'preco' => $livro['preco'],
                        ];
                    }, $livros->toArray()),
                ]
            ]);
    }
}
