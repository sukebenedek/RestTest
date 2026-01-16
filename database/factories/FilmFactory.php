<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FilmFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // Generál egy véletlen címet
            'director' => $this->faker->name(), // Generál egy nevet
            'release_year' => $this->faker->numberBetween(1900, 2024),
            'genre' => 'Drama',
            'rating' => 5.5,
        ];
    }
}
