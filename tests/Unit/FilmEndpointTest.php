<?php

use App\Models\Film;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/** @var \Tests\TestCase $this */
uses(TestCase::class, RefreshDatabase::class);

// ==========================================
// 1. VÉGPONT: GET /api/films (Index - Lista)
// ==========================================

test('GET index returns status 200 and a list of films', function () {
    // Factory-val létrehozunk 3 filmet
    Film::factory()->count(3)->create();

    $response = $this->getJson('/api/films');

    $response->assertStatus(200)
             ->assertJsonCount(3);
});

test('GET index returns correct data structure', function () {
    Film::factory()->create([
        'title' => 'Egyedi Cím',
    ]);

    $response = $this->getJson('/api/films');

    $response->assertJsonFragment(['title' => 'Egyedi Cím']);
});

test('GET index returns empty list when database is empty', function () {
    // Nem hozunk létre semmit

    $response = $this->getJson('/api/films');

    $response->assertStatus(200)
             ->assertJsonCount(0);
});

test('GET index headers are correct', function () {
    Film::factory()->create();

    $response = $this->getJson('/api/films');

    $response->assertHeader('Content-Type', 'application/json');
});

// ==========================================
// 2. VÉGPONT: POST /api/films (Store - Létrehozás)
// ==========================================

test('POST store creates a film successfully', function () {
    // Itt 'make'-et használunk, ami csak memóriában hozza létre az adatot, nem menti el (azt a POST teszi meg)
    $filmData = Film::factory()->make()->toArray();

    $response = $this->postJson('/api/films', $filmData);

    $response->assertStatus(201)
             ->assertJsonFragment(['title' => $filmData['title']]);

    $this->assertDatabaseHas('films', ['title' => $filmData['title']]);
});

test('POST store fails without required fields', function () {
    $response = $this->postJson('/api/films', [
        'director' => 'Hiányzik a Cím',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['title']);
});

test('POST store fails with invalid data types', function () {
    $invalidData = [
        'title' => 'Rossz Adat',
        'release_year' => 'nem-szám',
        'rating' => 15.0, // Túl nagy
    ];

    $response = $this->postJson('/api/films', $invalidData);

    $response->assertStatus(422);
});

test('POST store fails if rating is negative', function () {
    $invalidData = Film::factory()->make(['rating' => -1.0])->toArray();

    $response = $this->postJson('/api/films', $invalidData);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['rating']);
});

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

// ==========================================
// 3. VÉGPONT: GET /api/films/{id} (Show - Megtekintés)
// ==========================================

test('GET show returns a single film', function () {
    $film = Film::factory()->create();

    $response = $this->getJson("/api/films/{$film->id}");

    $response->assertStatus(200)
             ->assertJsonFragment(['id' => $film->id]);
});

test('GET show returns 404 for non-existent film', function () {
    $response = $this->getJson("/api/films/999999");

    $response->assertStatus(404);
});

test('GET show returns correct specific data', function () {
    $film = Film::factory()->create(['director' => 'Spielberg']);

    $response = $this->getJson("/api/films/{$film->id}");

    $response->assertJsonPath('director', 'Spielberg');
});

test('GET show handles invalid ID format gracefully', function () {
    $response = $this->getJson("/api/films/invalid-text-id");

    $response->assertStatus(404);
});

// ==========================================
// 4. VÉGPONT: DELETE /api/films/{id} (Destroy - Törlés)
// ==========================================

test('DELETE removes a film successfully', function () {
    $film = Film::factory()->create();

    $response = $this->deleteJson("/api/films/{$film->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('films', ['id' => $film->id]);
});

test('DELETE returns 404 if film not found', function () {
    $response = $this->deleteJson("/api/films/88888");

    $response->assertStatus(404);
});

test('DELETE only removes the specified film', function () {
    $filmToDelete = Film::factory()->create();
    $filmToKeep = Film::factory()->create();

    $this->deleteJson("/api/films/{$filmToDelete->id}");

    $this->assertDatabaseMissing('films', ['id' => $filmToDelete->id]);
    $this->assertDatabaseHas('films', ['id' => $filmToKeep->id]);
});

test('DELETE is idempotent (double delete)', function () {
    $film = Film::factory()->create();

    $this->deleteJson("/api/films/{$film->id}")->assertStatus(204);
    $this->deleteJson("/api/films/{$film->id}")->assertStatus(404);
});
