<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// EZ a lényeg, ennek itt kell lennie:
Route::apiResource('films', FilmController::class);
