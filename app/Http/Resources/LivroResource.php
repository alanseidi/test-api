<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivroResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codL' => $this->codL,
            'titulo' => $this->titulo,
            'editora' => $this->editora,
            'edicao' => $this->edicao,
            'anoPublicacao' => $this->anoPublicacao,
            'preco' => $this->preco,
            'assuntos' => array_map(function ($ssunto) {
                return [
                    'codAs' => $ssunto['codAs'],
                    'descricao' => $ssunto['descricao'],
                ];
            }, $this->assuntos->toArray()),
            'autores' => array_map(function ($autor) {
                return [
                    'codAu' => $autor['codAu'],
                    'nome' => $autor['nome'],
                ];
            }, $this->autores->toArray()),
        ];
    }
}
