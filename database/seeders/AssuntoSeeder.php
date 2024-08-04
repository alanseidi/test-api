<?php

namespace Database\Seeders;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Database\Seeder;

class AssuntoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Assunto::factory()
            ->count(10)
            ->create();

        Assunto::factory()
            ->count(30)
            ->has(
                Livro::factory()
                    ->count(3)
                    ->has(
                        Autor::factory()
                            ->count(rand(1, 3)),
                        'autores'
                    ),
                'livros'
            )
            ->create();
    }
}
