<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'price' => 'integer'
            ]
        );

        $product = Product::create($validateData);
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            //nincs ilyen id
            return response()->json(['message'=>"A(z) {$id} azonositoju termek nem letezik!"], Response::HTTP_NOT_FOUND);
        }
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            //nincs ilyen id
            return response()->json(['message'=>"A(z) {$id} azonositoju termek nem letezik!"], Response::HTTP_NOT_FOUND);
        }
        $validateData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'price' => 'integer'
            ]
        );

        $product->update($validateData);
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
