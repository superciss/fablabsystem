<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;



class OrdersController extends Controller
{
    /**
     * Display a listing of orders (newest first)
     */
    public function index()
    {
        $orders = Order::with('orderitem.product', 'user')
                        // ->where('approve_by_admin', 'approved')
                       ->orderBy('id', 'desc')
                       ->get();

        $products = Product::all();
        $customers = User::where('role', 'customer')->get();

        return view('admin.order.index', compact('orders', 'products'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'delivery_type' => 'required|in:pickup,delivery',
            'product_id.*'  => 'required|exists:product,id',
            'quantity.*'    => 'required|integer|min:1',
            'approve_by_admin' => 'nullable|in:approved,not approve',
            'created_at.*'  => 'nullable|date',
               'estimate_date'   => 'nullable|date',
            'type_request'    => 'nullable|in:cash,purchase request',
        ]);

         $user = User::where('id', $request->user_id)
                ->where('role', 'customer')
                ->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Selected user must be a customer.');
    }

        $total = 0;

        // ✅ Stock validation first
        foreach ($request->product_id as $key => $pid) {
            $product = Product::findOrFail($pid);
            $qty = $request->quantity[$key];

            if ($product->stock <= 0) {
                return redirect()->back()->with('error', "Product '{$product->name}' is out of stock!");
            }

            if ($qty > $product->stock) {
                return redirect()->back()->with('error', "Not enough stock for '{$product->name}'. Available: {$product->stock}");
            }

            $total += $product->price * $qty;
        }

        // Create order
        $order = Order::create([
            'user_id'       => $request->user_id,
            'delivery_type' => $request->delivery_type,
            'total_amount'  => $total,
            'status'        => 'pending',
            'order_number'  => 'ORD-' . time(),
            'approve_by_admin' => $request->approve_by_admin ?? 'not approve',
            'created_at'    => $request->created_at[0] ?? now(),
            'estimate_date'   => $request->estimate_date,
            'type_request'    => $request->type_request,

        ]);

        // Create order items and reduce stock
        foreach ($request->product_id as $key => $pid) {
            $product = Product::findOrFail($pid);
            $qty = $request->quantity[$key];

            $order->orderitem()->create([
                'product_id' => $pid,
                'quantity'   => $qty,
                'price'      => $product->price,
            ]);

            // Reduce product stock safely (never below 0)
            $product->decrement('stock', $qty);
        }

        return redirect()->back()->with('success', 'Order added successfully!');
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'delivery_type' => 'required|in:pickup,delivery',
            'status'        => 'required|in:pending,processing,completed,cancelled',
            'product_id.*'  => 'nullable|exists:product,id',
            'quantity.*'    => 'nullable|integer|min:1',
            'approve_by_admin' => $request->approve_by_admin ?? 'not approve',
             'created_at.*'  => 'nullable|date',
            'estimate_date'   => 'nullable|date',
            'type_request'    => 'nullable|in:cash,purchase request',
        ]);

        $order->update([
            'delivery_type' => $request->delivery_type,
            'status'        => $request->status,
            'created_at'    => $request->created_at[0] ?? now(),
            'estimate_date'   => $request->estimate_date,
            'type_request'    => $request->type_request,
        ]);

        if ($request->has('product_id') && count($request->product_id) > 0) {
            $total = 0;

            foreach ($request->product_id as $key => $pid) {
                $product = Product::findOrFail($pid);
                $qty = $request->quantity[$key];

                if ($product->stock <= 0) {
                    return redirect()->back()->with('error', "Product '{$product->name}' is out of stock!");
                }

                if ($qty > $product->stock) {
                    return redirect()->back()->with('error', "Not enough stock for '{$product->name}'. Available: {$product->stock}");
                }

                $order->orderitem()->updateOrCreate(
                    ['product_id' => $pid],
                    ['quantity' => $qty, 'price' => $product->price]
                );

                $total += $product->price * $qty;
            }

            $order->update(['total_amount' => $total]);
        }

        return redirect()->back()->with('success', 'Order updated successfully!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully!');
    }


    
    public function bulkUpdate(Request $request)
    {
        $ids = $request->ids;
        $status = $request->status;

        Order::whereIn('id', $ids)->update(['status' => $status]);

        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        Order::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }


      public function bulkApprove(Request $request)
    {
        $ids = $request->ids ?? [];
        $approve = $request->approve_by_admin ?? null;

        if (in_array($approve, ['approved', 'not approve']) && count($ids) > 0) {
            Order::whereIn('id', $ids)->update(['approve_by_admin' => $approve]);
        }

        return response()->json(['success' => true]);
    }

}
