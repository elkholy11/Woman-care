<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        return CartResource::collection(Cart::where('user_id', Auth::id())->get());
    }

    public function store(CartRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $cart = Cart::create($data);
        return new CartResource($cart);
    }

    public function update(CartRequest $request, Cart $cart)
    {
      //  $this->authorize('update', $cart);

        $cart->update($request->validated());
        return new CartResource($cart);
    }

    public function destroy(Cart $cart)
    {
        $this->authorize('delete', $cart);

        $cart->delete();
        return response()->json(['message' => 'Cart item deleted']);
    }
}
