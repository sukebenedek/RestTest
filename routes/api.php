<?php

use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/category',[ShopController::class,'allCategory']);
Route::get('/product',[ShopController::class,'allProduct']);
Route::get('/categoryWithProduct',[ShopController::class,'categoryWithProduct']);

Route::get('/filterProductByCategory/{id}',[ShopController::class,'filterProductByCategory']);
Route::get('/filterProductByName/{name}',[ShopController::class,'filterProductByName']);

Route::get('/listOrders/{id?}',[ShopController::class,'listOrders']);
