<?php

namespace App\Services;


use App\Exceptions\SyncFailException;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Support\Facades\DB;

class LivroAutorService
{


    /**
     * @throws SyncFailException
     */
    public function syncLivroAutor($data)
    {
        DB::beginTransaction();
        try {
            $livro = Livro::find($data['codL']);
            $livro->autores()->sync($data['arrayCodAu']);

            DB::commit();

            return $livro;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new SyncFailException('Falha ao associar o(s) autor(es) ao livro: '.$e->getMessage());
        }
    }
}
