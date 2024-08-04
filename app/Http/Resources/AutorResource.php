<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codAu' => $this->codAu,
            'nome' => $this->nome,
            'livros' => array_map(function ($livro) {
                return [
                    'codL' => $livro['codL'],
                    'titulo' => $livro['titulo'],
                    'editora' => $livro['editora'],
                    'edicao' => $livro['edicao'],
                    'anoPublicacao' => $livro['anoPublicacao'],
                ];
            }, $this->livros->toArray()),
        ];
    }

}
