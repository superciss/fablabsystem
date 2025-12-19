<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaffSaleController extends Controller
{
  public function index()
{
    $products = Product::where('stock', '>', 0)->get();

    $completedOrders = Order::with('user', 'orderitem.product')
        ->where('status', 'completed')
        ->get()
        ->filter(fn($order) => $order->orderitem->count() > 0 && $order->orderitem->sum(fn($item) => $item->quantity * $item->price) > 0);

    // --- Stock per category ---
    $rawStock       = Product::whereHas('category', fn($q) => $q->where('name', 'Raw Material'))->sum('stock');
    $wholesaleStock = Product::whereHas('category', fn($q) => $q->where('name', 'Wholesale'))->sum('stock');
    $finishedStock  = Product::whereHas('category', fn($q) => $q->where('name', 'Finished Product'))->sum('stock');

    // --- Low stock counts per category ---
    $lowStockRaw       = Product::whereHas('category', fn($q) => $q->where('name', 'Raw Material'))->where('stock', '<', 5)->count();
    $lowStockWholesale = Product::whereHas('category', fn($q) => $q->where('name', 'Wholesale'))->where('stock', '<', 5)->count();
    $lowStockFinished  = Product::whereHas('category', fn($q) => $q->where('name', 'Finished Product'))->where('stock', '<', 5)->count();

    // --- Profit Today ---
    $todayOrders = $completedOrders->filter(fn($order) => $order->created_at->isToday());
    $profitToday = $todayOrders->sum(fn($order) => 
        $order->orderitem->sum(fn($item) => $item->price * $item->quantity)
    );

    // --- Profit Yesterday ---
    $yesterdayOrders = $completedOrders->filter(fn($order) => $order->created_at->isYesterday());
    $profitYesterday = $yesterdayOrders->sum(fn($order) => 
        $order->orderitem->sum(fn($item) => $item->price * $item->quantity)
    );

    // --- Recently updated products (with stock prediction) ---
    $recentProducts = Product::with('category')
        ->orderBy('updated_at', 'desc')
        ->take(5)
        ->get()
        ->map(function ($product) {
            $sales = OrderItem::where('product_id', $product->id)->get();
            $totalSold = $sales->sum('quantity');
            $firstSale = $sales->min('created_at');
            $days = $firstSale ? max(1, now()->diffInDays($firstSale)) : 1;
            $avgDailySales = $days > 0 ? $totalSold / $days : 0;

            $estimatedDays = match(true) {
                $product->stock <= 0 => 'Out',
                $avgDailySales <= 0 => 'Stable',
                default => round($product->stock / $avgDailySales, 1),
            };

            $product->avgDailySales = $avgDailySales;
            $product->estimatedDays = $estimatedDays;

            return $product;
        });

    return view('staff.sale.index', compact(
        'products', 'completedOrders',
        'rawStock', 'wholesaleStock', 'finishedStock',
        'lowStockRaw', 'lowStockWholesale', 'lowStockFinished',
        'profitToday', 'profitYesterday', 'recentProducts'
    ));
}



    // Direct sale payment
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
            'staff_name' => auth()->user()->role === 'staff' ? auth()->user()->name : null,
            'total_amount' => $total,
            'discount' => 0,
            'tax' => 0,
            'grand_total' => $total,
            'amount_paid' => $validated['cash'],
            'change_due' => $validated['cash'] - $total,
            'status' => 'completed',
            'approve_by_admin' => 'approved',
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

        return redirect()->route('staff.sale.index')
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

        return redirect()->route('staff.sale.index')
            ->with('success', 'Online orders payment processed successfully.');
    }
}
