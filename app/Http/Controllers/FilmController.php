<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FilmController extends Controller
{
    // GET /api/films
    // List all films
    public function index()
    {
        return response()->json(Film::all(), 200);
    }

    // POST /api/films
    // Create a new film
    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'release_year' => 'required|integer|min:1888|max:' . (date('Y') + 5),
            'genre' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $film = Film::create($validated);

        // Return 201 Created status
        return response()->json($film, 201);
    }

    // GET /api/films/{id}
    // Show a specific film
    public function show(string $id)
    {
        $film = Film::find($id);

        if (!$film) {
            return response()->json(['message' => 'Film not found'], 404);
        }

        return response()->json($film, 200);
    }

    // PUT/PATCH /api/films/{id}
    // Update a film
    public function update(Request $request, string $id)
    {
        $film = Film::find($id);

        if (!$film) {
            return response()->json(['message' => 'Film not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'director' => 'sometimes|string|max:255',
            'release_year' => 'sometimes|integer',
            'genre' => 'sometimes|string',
            'rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $film->update($validated);

        return response()->json($film, 200);
    }

    // DELETE /api/films/{id}
    // Delete a film
    public function destroy(string $id)
    {
        $film = Film::find($id);

        if (!$film) {
            return response()->json(['message' => 'Film not found'], 404);
        }

        $film->delete();

        // Return 204 No Content (Standard for delete)
        return response()->json(null, 204);
    }
}
