<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
       $orders   = Order::with('orderitem.product','user')
                     ->orderBy('id', 'desc')
                     ->get();

        $users    = User::all();
        $products = Product::all();

        return view('admin.order.index', compact('orders','users','products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'delivery_type'    => 'required|in:pickup,delivery',
            'delivery_status'  => 'required|in:is_ongoing,is_upcoming,for_pickup,for_delivery',
            'product_id.*'     => 'nullable|exists:products,id',
            'quantity.*'       => 'nullable|integer|min:1',
            'approve_by_admin' => 'nullable|in:approved,not approve',
            'estimate_date'   => 'nullable|date',
            'type_request'    => 'nullable|in:cash,purchase request',
            'created_at.*'     => 'nullable|date',
        ]);

        $total = 0;

        foreach ($request->product_id as $key => $productId) {
            $quantity = $request->quantity[$key] ?? 0;
            if ($productId && $quantity > 0) {
                $product = Product::find($productId);
                if (!$product) continue;

                if ($quantity > $product->stock) {
                    return redirect()->back()->with('error', "Not enough stock for product {$product->name}. Available: {$product->stock}");
                }
                $total += $product->price * $quantity;
            }
        }

        $order = Order::create([
            'order_number'     => 'ORD-' . time(),
            'user_id'          => $request->user_id,
            'status'           => 'pending',
            'delivery_type'    => $request->delivery_type,
            'delivery_status'  => $request->delivery_status,
            'total_amount'     => $total,
            'approve_by_admin' => $request->approve_by_admin ?? 'not approve',
            // 'estimate_date'   => $request->estimate_date,
            // 'type_request'    => $request->type_request,
           // 'created_at'       => $request->created_at[0] ?? now(),
        ]);

        foreach ($request->product_id as $key => $productId) {
            $quantity = $request->quantity[$key] ?? 0;
            if ($productId && $quantity > 0) {
                $product = Product::find($productId);
                if ($product) {
                    $product->decrement('stock', $quantity);
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $productId,
                        'quantity'   => $quantity,
                        'price'      => $product->price,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Order created successfully!');
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'delivery_type'    => 'required|in:pickup,delivery',
           'delivery_status'  => 'required|in:is_ongoing,is_upcoming,for_pickup,for_delivery',
            'status'           => 'required|in:pending,processing,completed,cancelled',
            'product_id.*'     => 'nullable|exists:products,id',
            'quantity.*'       => 'nullable|integer|min:1',
            'approve_by_admin' => 'nullable|in:approved,not approve',
            'estimate_date'   => 'nullable|date',
            'type_request'    => 'nullable|in:cash,purchase request',
            // 'created_at.*'     => 'nullable|date',
        ]);

        $total = 0;

        foreach ($request->product_id as $key => $productId) {
            $quantity = $request->quantity[$key] ?? 0;
            if ($productId && $quantity > 0) {
                $product = Product::find($productId);
                if (!$product) continue;

                if ($quantity > $product->stock) {
                    return redirect()->back()->with('error', "Not enough stock for product {$product->name}. Available: {$product->stock}");
                }
                $total += $product->price * $quantity;
            }
        }

        $order->orderitem()->delete();

        foreach ($request->product_id as $key => $productId) {
            $quantity = $request->quantity[$key] ?? 0;
            if ($productId && $quantity > 0) {
                $product = Product::find($productId);
                if ($product) {
                    $product->decrement('stock', $quantity);
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $productId,
                        'quantity'   => $quantity,
                        'price'      => $product->price,
                    ]);
                }
            }
        }

        $order->update([
            'user_id'          => $request->user_id,
            'delivery_type'    => $request->delivery_type,
            'delivery_status'  => $request->delivery_status,
            'status'           => $request->status,
            'total_amount'     => $total,
            'approve_by_admin' => $request->approve_by_admin ?? 'not approve',
            'estimate_date'   => $request->estimate_date,
            'type_request'    => $request->type_request,
            // 'created_at'       => $request->created_at[0] ?? now(),
        ]);

        return redirect()->back()->with('success', 'Order updated successfully!');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted!');
    }

    /* ============================
       BULK ACTIONS
    ============================ */

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if (count($ids) > 0) {
            Order::whereIn('id', $ids)->delete();
        }
        return response()->json(['success' => true]);
    }

    public function bulkUpdate(Request $request)
    {
        $ids = $request->ids ?? [];
        $status = $request->status ?? null;

        if ($status && count($ids) > 0) {
            Order::whereIn('id', $ids)->update(['status' => $status]);
        }

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

    public function bulkPaid(Request $request)
        {
            $ids = $request->ids;

            Order::whereIn('id', $ids)->update([
                'paid' => true,  
            ]);

            return response()->json(['success' => true]);
        }


}
