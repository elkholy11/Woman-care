<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'user'])->get(); // عرض كل الطلبات
        return OrderResource::collection($orders);
    }

    public function store(OrderRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validated();

            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => $validated['status'] ?? 'pending',
                'total_price' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemTotal = $product->price * $item['quantity'];
                $total += $itemTotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            $order->update(['total_price' => $total]);

            return new OrderResource($order->load('items.product'));
        });
    }

    public function show(Order $order)
    {
       // $this->authorize('view', $order); // optional
        return new OrderResource($order->load('items.product'));
    }

    public function update(OrderRequest $request, Order $order)
    {
         $order->items()->delete();

    $total = 0;

    foreach ($request->validated()['items'] as $item) {
        $product = Product::findOrFail($item['product_id']);
        $price = $product->price;
        $quantity = $item['quantity'];
        $subtotal = $price * $quantity;

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal,
        ]);

        $total += $subtotal;
    }

    $order->update(['total_price' => $total]);

    return new OrderResource($order->load('items.product'));
    }

    public function updateStatus(OrderRequest $request, Order $order)
{
    $validated = $request->validated();

    $order->status = (string) $validated['status']; // تحويل صريح لنص
    $order->save();

    return response()->json([
        'message' => 'Order status updated successfully.',
        'order' => new OrderResource($order)
    ]);
}
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order); // optional

        $order->delete();

        return response()->json(['message' => 'Order deleted']);
    }
}
