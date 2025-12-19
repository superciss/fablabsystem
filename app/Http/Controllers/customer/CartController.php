<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show cart
    public function index()
    {
        $cart = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        return view('customer.cart.index', compact('cart'));
    }

    // Add to cart
    public function add(Request $request, Product $product)
    {
        $quantity = (int) $request->quantity;

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id'   => Auth::id(),
                'product_id'=> $product->id,
                'quantity'  => $quantity,
            ]);
        }

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success'   => true,
            'message'   => 'Product added to cart!',
            'cartCount' => $cartCount,
        ]);
    }

    // Remove from cart
    public function remove(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        $subtotal = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->sum(fn($i) => $i->product->price * $i->quantity);

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success'   => true,
            'subtotal'  => $subtotal,
            'cartCount' => $cartCount
        ]);
    }

    // Update cart item quantity
    public function updateQuantity(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $quantity = max(1, (int)$request->quantity);
        $cartItem->update(['quantity' => $quantity]);

        $subtotal = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->sum(fn($i) => $i->product->price * $i->quantity);

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success'   => true,
            'quantity'  => $cartItem->quantity,
            'rowTotal'  => $cartItem->product->price * $cartItem->quantity,
            'subtotal'  => $subtotal,
            'cartCount' => $cartCount,
        ]);
    }

    // Return cart count for badge
    public function cartCount()
    {
        $cartCount = auth()->check() ? auth()->user()->cartitem()->sum('quantity') : 0;
        return response()->json(['count' => $cartCount]);
    }

    // Checkout
   public function checkout(Request $request)
{
    $cart = CartItem::where('user_id', Auth::id())
        ->with('product')
        ->get();

    if ($cart->isEmpty()) {
        return redirect()->back()->with('error', 'Cart is empty!');
    }

    foreach ($cart as $item) {
        if (!$item->product || $item->product->stock < $item->quantity) {
            return redirect()->back()->with('error', "Sorry, {$item->product->name} does not have enough stock.");
        }
    }

    $deliveryType = $request->input('delivery_type', 'pickup'); // ✅ read from form
    $typeRequest  = $request->input('type_request');            // cash or purchase request
    $order = Order::create([
        'order_number'  => 'ORD-' . strtoupper(uniqid()),
        'user_id'       => Auth::id(),
        'status'        => 'pending',
        'delivery_type' => $deliveryType, // ✅ save chosen type
        'type_request'  => $typeRequest,
        'total_amount'  => $cart->sum(fn($i) => $i->product->price * $i->quantity),
    ]);

    foreach ($cart as $item) {
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $item->product_id,
            'quantity'   => $item->quantity,
            'price'      => $item->product->price,
        ]);

        $item->product->decrement('stock', $item->quantity);
    }

    CartItem::where('user_id', Auth::id())->delete();

    return redirect()->route('customer.orderlist.index')
        ->with('success', 'Order placed successfully!');
}

// Bulk remove items from cart
public function bulkRemove(Request $request)
{
    $ids = $request->input('ids', []); // array of cart item IDs

    if (!empty($ids)) {
        CartItem::where('user_id', Auth::id())
                ->whereIn('id', $ids)
                ->delete();
    }

    $subtotal = CartItem::where('user_id', Auth::id())
        ->with('product')
        ->get()
        ->sum(fn($i) => $i->product->price * $i->quantity);

    $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

    return response()->json([
        'success'   => true,
        'subtotal'  => $subtotal,
        'cartCount' => $cartCount,
    ]);
}

public function bulkCheckout(Request $request)
{
    $ids = $request->input('ids', []);

    $cart = CartItem::where('user_id', Auth::id())
        ->whereIn('id', $ids)
        ->with('product')
        ->get();

    if ($cart->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'No items selected for checkout.']);
    }

    foreach ($cart as $item) {
        if (!$item->product || $item->product->stock < $item->quantity) {
            return response()->json([
                'success' => false,
                'message' => "Sorry, {$item->product->name} does not have enough stock."
            ]);
        }
    }

    $deliveryType = $request->input('delivery_type', 'pickup');

    $totalAmount = $cart->sum(fn($i) => $i->product->price * $i->quantity);

    $order = Order::create([
        'order_number'  => 'ORD-' . strtoupper(uniqid()),
        'user_id'       => Auth::id(),
        'status'        => 'pending',
        'delivery_type' => $deliveryType,
        'total_amount'  => $totalAmount,
    ]);

    foreach ($cart as $item) {
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $item->product_id,
            'quantity'   => $item->quantity,
            'price'      => $item->product->price,
        ]);

        $item->product->decrement('stock', $item->quantity);
    }

    CartItem::where('user_id', Auth::id())->whereIn('id', $ids)->delete();

    // Return new subtotal & cart count
    $subtotal = CartItem::where('user_id', Auth::id())
        ->with('product')
        ->get()
        ->sum(fn($i) => $i->product->price * $i->quantity);

    $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

    return response()->json([
        'success' => true,
        'message' => 'Selected items checked out successfully!',
        'subtotal' => $subtotal,
        'cartCount' => $cartCount,
    ]);
}

}
