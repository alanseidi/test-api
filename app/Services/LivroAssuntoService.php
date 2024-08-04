<?php

namespace App\Services;

use App\Exceptions\SyncFailException;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Support\Facades\DB;

class LivroAssuntoService
{
    /**
     * @throws SyncFailException
     */
    public function syncLivroAssunto($data)
    {
        DB::beginTransaction();
        try {
            $livro = Livro::find($data['codL']);
            $livro->assuntos()->sync($data['arrayCodAs']);

            DB::commit();

            return $livro;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new SyncFailException('Falha ao associar o(s) assunto(s) ao livro: '.$e->getMessage());
        }
    }

    /**
     * @throws SyncFailException
     */
    public function syncAssuntoLivro($data)
    {
        DB::beginTransaction();
        try {
            $assunto = Assunto::find($data['codAs']);
            $assunto->livros()->sync($data['arrayCodL']);

            DB::commit();

            return $assunto;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new SyncFailException('Falha ao associar o(s) livro(s) ao assunto: '.$e->getMessage());
        }
    }
}
