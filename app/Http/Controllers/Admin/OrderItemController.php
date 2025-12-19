<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with(['order', 'product'])->get();
        $orders = Order::all();
        $products = Product::all();
        return view('admin.orderitem.index', compact('orderItems', 'orders', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        OrderItem::create($request->all());

        return redirect()->back()->with('success', 'Order item added successfully!');
    }

    public function update(Request $request, OrderItem $orderitem)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $orderitem->update($request->all());

        return redirect()->back()->with('success', 'Order item updated successfully!');
    }

    public function destroy(OrderItem $orderitem)
    {
        $orderitem->delete();
        return redirect()->back()->with('success', 'Order item deleted successfully!');
    }
}
