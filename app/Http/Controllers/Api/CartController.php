<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
                         ->where('user_id', Auth::id())
                         ->get();

        return CartResource::collection($cartItems);
    }

    public function store(CartRequest $request)
    {
        $data = $request->validated();
        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $data['product_id']
            ],
            [
                'quantity' => $data['quantity']
            ]
        );

        return new CartResource($cart->load('product'));
    }
    public function show($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        return new CartResource($cart->load('product'));
    }

    public function update(CartRequest $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->update($request->validated());
        return new CartResource($cart->load('product'));
    }

    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();
        return response()->json(['message' => 'Item removed from cart']);
    }

   // public function decreaseQuantity($id)
   // {
     //   $cart = Cart::where('user_id', Auth::id())->findOrFail($id);

    //    if ($cart->quantity > 1) {
     //       $cart->decrement('quantity');
    //        return new CartResource($cart->fresh()->load('product'));
    //    } else {
     //       $cart->delete();
      //      return response()->json(['message' => 'Item removed from cart (quantity reached 0)']);
      //  }
  //  }
}
