<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::with(['category', 'brand'])->get());
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());
        return new ProductResource($product->load(['category', 'brand']));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load(['category', 'brand']));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return new ProductResource($product->load(['category', 'brand']));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}

