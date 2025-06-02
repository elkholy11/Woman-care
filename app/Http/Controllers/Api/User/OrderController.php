<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\OrderRequest;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->where('user_id', auth()->id())->get();
        return OrderResource::collection($orders);
    }

    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $total = 0;

        DB::beginTransaction();

        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total_price' => 0
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = $product->price * $item['quantity'];
            $total += $price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $price,
            ]);
        }

        $order->update(['total_price' => $total]);

        DB::commit();

        return new OrderResource($order->load('items.product'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new OrderResource($order->load('items.product'));
    }
}

