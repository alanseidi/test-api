<?php

namespace Database\Seeders;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Autor::factory()
            ->count(10)
            ->create();

        Autor::factory()
            ->count(10)
            ->has(
                Livro::factory()
                    ->count(rand(1, 5))
                    ->has(
                        Assunto::factory()
                            ->count(rand(1, 5)),
                        'assuntos'
                    ),
                'livros'
            )
            ->create();
    }
}
