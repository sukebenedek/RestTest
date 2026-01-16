<?php

use App\Models\Film;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/** @var \Tests\TestCase $this */
uses(TestCase::class, RefreshDatabase::class);

// ==========================================
// 3. VÉGPONT: PUT/PATCH /api/films/{id} (Módosítás)
// ==========================================

test('PUT update modifies a film successfully', function () {
    // 1. Kézzel létrehozunk egy filmet (így nem kell Factory)
    $film = Film::create([
        'title' => 'Régi Cím',
        'director' => 'Régi Rendező',
        'release_year' => 2000,
        'genre' => 'Comedy',
        'rating' => 5.0,
    ]);

    // 2. Módosítjuk az adatait
    $updatedData = [
        'title' => 'Frissített Cím',
        'director' => 'Új Rendező',
        'release_year' => 2025,
        'genre' => 'Drama',
        'rating' => 9.5,
    ];

    $response = $this->putJson("/api/films/{$film->id}", $updatedData);

    // 3. Ellenőrizzük
    $response->assertStatus(200)
             ->assertJsonFragment(['title' => 'Frissített Cím']);

    $this->assertDatabaseHas('films', ['title' => 'Frissített Cím']);
});

test('PATCH can partially update a film', function () {
    $film = Film::create([
        'title' => 'Eredeti Cím',
        'director' => 'Maradjon Ez',
        'release_year' => 2022,
        'genre' => 'Action',
        'rating' => 7.0,
    ]);

    // Csak a címet küldjük be (PATCH)
    $response = $this->patchJson("/api/films/{$film->id}", [
        'title' => 'Csak a cím változott',
    ]);

    $response->assertStatus(200);

    // A rendezőnek változatlannak kell lennie az adatbázisban
    $this->assertDatabaseHas('films', [
        'id' => $film->id,
        'title' => 'Csak a cím változott',
        'director' => 'Maradjon Ez',
    ]);
});

test('update fails if validation rules are broken', function () {
    $film = Film::create([
        'title' => 'Valid Film',
        'director' => 'Valid Rendező',
        'release_year' => 2020,
        'genre' => 'Sci-Fi',
        'rating' => 8.0,
    ]);

    // Üres cím és érvénytelen évszám
    $invalidData = [
        'title' => '',
        'release_year' => 'nem-szám',
    ];

    $response = $this->putJson("/api/films/{$film->id}", $invalidData);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['title', 'release_year']);
});

test('cannot update a film that does not exist', function () {
    $nonExistentId = 99999;

    $response = $this->putJson("/api/films/{$nonExistentId}", [
        'title' => 'Ez nem fog menni',
    ]);

    $response->assertStatus(404);
});
