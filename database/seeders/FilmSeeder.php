<?php

namespace Database\Seeders;

use App\Models\Film;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        $films = [
            [
                'title' => 'A Végső Commit',
                'director' => 'Kovács "Git" János',
                'release_year' => 2023,
                'genre' => 'Thriller',
                'rating' => 9.2,
            ],
            [
                'title' => 'Végtelen Ciklus',
                'director' => 'Anna Loop',
                'release_year' => 2021,
                'genre' => 'Horror',
                'rating' => 7.5,
            ],
            [
                'title' => 'A Laravel Mesterei',
                'director' => 'Taylor O.',
                'release_year' => 2024,
                'genre' => 'Documentary',
                'rating' => 10.0,
            ],
            [
                'title' => 'Bug a Rendszerben',
                'director' => 'Peter Syntax',
                'release_year' => 2019,
                'genre' => 'Sci-Fi',
                'rating' => 6.8,
            ],
            [
                'title' => 'Hétfő Reggel: A Deploy',
                'director' => 'Sarah Panic',
                'release_year' => 2022,
                'genre' => 'Drama',
                'rating' => 8.1,
            ],
            [
                'title' => 'Az Elveszett Pontosvessző',
                'director' => 'John C. Plus',
                'release_year' => 2010,
                'genre' => 'Adventure',
                'rating' => 5.5,
            ],
            [
                'title' => 'Full Stack Szerelem',
                'director' => 'Emily Frontend',
                'release_year' => 2020,
                'genre' => 'Romance',
                'rating' => 7.9,
            ],
            [
                'title' => 'A NullPointerException Rejtélye',
                'director' => 'Agatha Java',
                'release_year' => 2015,
                'genre' => 'Mystery',
                'rating' => 8.8,
            ],
        ];

        foreach ($films as $film) {
            Film::create($film);
        }
    }
}
