<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Livro>
 */
class LivroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->realTextBetween(10, 30),
            'editora' => $this->faker->company(),
            'edicao' => $this->faker->numberBetween(100, 9999),
            'anoPublicacao' => $this->faker->year(),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
