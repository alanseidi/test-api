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
            'titulo' => $this->faker->text(30),
            'editora' => $this->faker->text(30),
            'edicao' => $this->faker->numberBetween(100, 9999),
            'anoPublicacao' => $this->faker->year(),
        ];
    }
}
