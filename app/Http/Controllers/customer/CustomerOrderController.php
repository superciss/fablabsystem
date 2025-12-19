<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Custom;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    // Show all orders for the logged-in customer
    public function index()
    {
        $orders = Order::with('orderItem.product', 'user.userInformation')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.orderlist.index', compact('orders'));
    }

    // Buy Now action
    public function buyNow(Request $request, Product $product)
    {
        $quantity = (int) $request->input('quantity', 1);
        $deliveryType = $request->input('delivery_type', 'pickup'); // pickup or delivery
        $typeRequest   = $request->input('type_request');            // cash or purchase request
        $estimateDate  = $request->input('estimate_date');           // optional date

        // Check stock
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // Create Order
        $order = Order::create([
            'order_number'  => 'ORD-' . strtoupper(uniqid()),
            'user_id'       => Auth::id(),
            'status'        => 'pending',
            'delivery_type' => $deliveryType,
            'type_request'  => $typeRequest,       // ✅ added
            'estimate_date' => $estimateDate,      // ✅ added
            'total_amount'  => $product->price * $quantity,
        ]);

        // Create Order Item
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => $quantity,
            'price'      => $product->price,
        ]);

        // Decrease product stock
        $product->decrement('stock', $quantity);

        return redirect()->route('customer.orderlist.index')
            ->with('success', 'Product bought successfully!');
    }

    // Cancel order
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be cancelled.');
        }

        // Restore stock
        foreach ($order->orderItem as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->status = 'cancelled';
        $order->save();

        return redirect()->back()->with('success', 'Order cancelled successfully and stock restored.');
    }
}
