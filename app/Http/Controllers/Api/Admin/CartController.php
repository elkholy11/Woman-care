<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        return CartResource::collection(Cart::with('product', 'user')->get());
    }

    public function show(Cart $cart)
    {
        return new CartResource($cart->load(['product', 'user']));
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return response()->json(['message' => 'Cart item deleted']);
    }
}

