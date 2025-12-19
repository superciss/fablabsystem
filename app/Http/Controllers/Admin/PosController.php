<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PosController extends Controller
{
    // Show POS page
    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();

        // Include completed orders with items
        $completedOrders = Order::with('user', 'orderitem.product')
            ->where('status', 'completed')
            ->get()
            ->filter(function ($order) {
                return $order->orderitem->count() > 0 && $order->orderitem->sum(fn($item) => $item->quantity * $item->price) > 0;
            });

        return view('admin.sale.pos', compact('products', 'completedOrders'));
    }

   // Direct POS payment
public function store(Request $request)
{
    $validated = $request->validate([
        'items' => 'required|array',
        'cash' => 'required|numeric|min:0',
    ]);

    $total = 0;
    foreach ($validated['items'] as $item) {
        $product = Product::find($item['product_id']);
        if (!$product || $item['quantity'] <= 0) continue;
        $total += $product->price * $item['quantity'];
    }

    if ($total <= 0) {
        return back()->withErrors(['items' => 'Please select at least one product with quantity greater than zero.'])->withInput();
    }

    if ($validated['cash'] < $total) {
        return back()->withErrors(['cash' => 'Cash provided is not enough.'])->withInput();
    }

    $orderNumber = 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

    $order = Order::create([
        'order_number' => $orderNumber,
        'user_id' => auth()->id(),
        'total_amount' => $total,
        'discount' => 0,
        'tax' => 0,
        'grand_total' => $total,
        'amount_paid' => $validated['cash'],
        'change_due' => $validated['cash'] - $total,
        'status' => 'completed',
        'approve_by_admin' => 'approved' // Automatically approve
    ]);

    foreach ($validated['items'] as $item) {
        $product = Product::find($item['product_id']);
        if (!$product || $item['quantity'] <= 0) continue;

        $order->orderitem()->create([
            'product_id' => $product->id,
            'quantity' => $item['quantity'],
            'price' => $product->price,
            'subtotal' => $product->price * $item['quantity'],
        ]);

        $product->decrement('stock', $item['quantity']);
    }

    return redirect()->route('admin.sale.pos')
        ->with('success', "Payment successful. Change: ₱" . number_format($order->change_due, 2));
}

    // Process online orders
    public function onlinepay(Request $request)
    {
        $validated = $request->validate([
            'cash' => 'required|array'
        ]);

        foreach ($validated['cash'] as $orderId => $cashReceived) {
            $order = Order::with('orderitem.product')->find($orderId);
            if (!$order) continue;

            $total = $order->orderitem->sum(fn($item) => $item->quantity * $item->price);
            if ($total <= 0) continue;

            $paid = (float)$cashReceived;

            // Generate order number if missing
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
            }

            $order->update([
                'amount_paid' => $paid,
                'change_due' => $paid - $total,
                'status' => 'completed',
                'order_number' => $order->order_number
            ]);
        }

        return redirect()->route('admin.sale.pos')
            ->with('success', 'Online orders payment processed successfully.');
    }
}
