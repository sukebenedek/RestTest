<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('tasks', TaskController::class)->missing(
    function(Request $request) {
        return response()->json(
            ['message'=>'A megadott azonosítójú feladat nem található!'],
            404
        );
    }
);

Route::apiResource('products', ProductController::class)->missing(
    function(Request $request) {
        return response()->json(
            ['message'=>'A megadott azonosítójú feladat nem található!'],
            404
        );
    }
);
