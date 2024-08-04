<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_relatorio AS
                SELECT
                    au.codAu,
                    au.nome,
                    l.codL,
                    l.titulo,
                    l.editora,
                    l.edicao,
                    l.preco,
                    l.anoPublicacao,
                    l.assunto
                FROM autor AS au
                LEFT JOIN laravel.livro_autor la ON au.codAu = la.autor_codAu
                LEFT JOIN (
                    SELECT
                        l.codL,
                        l.titulo,
                        l.editora,
                        l.edicao,
                        l.preco,
                        l.anoPublicacao,
                        GROUP_CONCAT(DISTINCT a.descricao SEPARATOR ', ') AS assunto
                    FROM livro AS l
                             INNER JOIN laravel.livro_assunto la ON l.codL = la.livro_codL
                             INNER JOIN laravel.assunto a ON la.assunto_codAs = a.codAs
                    GROUP BY l.codL
                ) AS l ON l.codL = la.livro_codL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW view_relatorio");
    }
};
