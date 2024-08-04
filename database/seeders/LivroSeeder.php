<?php

namespace Database\Seeders;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LivroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Livro::factory()
            ->count(100)
            ->has(Assunto::factory()->count(rand(1, 5)), 'assuntos')
            ->has(Autor::factory()->count(rand(1, 3)), 'autores')
            ->create();
    }
}
