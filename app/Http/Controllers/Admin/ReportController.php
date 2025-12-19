<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Machine;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
       

        // --- Filters ---
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');
        $status   = $request->input('status');
        $delivery = $request->input('delivery_type');

        // --- Orders ---
        $orders = Order::with(['user', 'orderItem.product'])
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($delivery, fn($q) => $q->where('delivery_type', $delivery))
            ->get();

        // --- Prepare ordersData for JS directly ---
        $ordersData = $orders->mapWithKeys(function($order) {
            return [
                $order->id => [
                    'number' => $order->order_number,
                    'date' => $order->created_at->format('Y-m-d H:i'),
                    'customer' => $order->user->name ?? 'N/A',
                    'delivery' => ucfirst($order->delivery_type),
                    'status' => ucfirst($order->status),
                    'total' => number_format($order->total_amount, 2),
                    'items' => $order->orderItem->map(function($i) {
                        return [
                            'name' => $i->product->name ?? 'N/A',
                            'qty' => $i->quantity,
                            'price' => number_format($i->price, 2),
                            'subtotal' => number_format($i->quantity * $i->price, 2)
                        ];
                    })->toArray()
                ]
            ];
        })->toArray();

        // --- Sales Reports ---
        $totalRevenue = $orders->where('status', 'completed')->sum('total_amount');
        $ordersByStatus = $orders->groupBy('status')->map->count();
        $salesByDelivery = $orders->groupBy('delivery_type')->map->count();

        $topProducts = OrderItem::with('product.category')
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'product'  => $product->name,
                    'unit'     => $product->unit,
                    'category' => $product->category?->name ?? 'N/A',
                    'total'    => $items->sum('quantity'),
                ];
            })
            ->sortByDesc('total')
            ->take(5);

        // --- Inventory Reports ---
        $lowStock = Product::where('stock', '<=', 5)->with('category')->get();
        $stockLevels = Product::with('category')->select('id', 'name', 'stock', 'unit', 'category_id')->get();

        // --- Purchase Reports ---
        $purchases = Purchase::with('supplier')
            ->when($dateFrom, fn($q) => $q->whereDate('purchase_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('purchase_date', '<=', $dateTo))
            ->get();

        $totalPurchases = $purchases->sum('total_cost');
        $purchasesByStatus = $purchases->groupBy('status')->map->count();

        // --- Supplier Reports ---
        $suppliers = Supplier::all();
            
        $purchaseVolumePerSupplier = Supplier::with('products')->get()->map(function ($supplier) use ($purchases) {
            $total = $purchases->where('supplier_id', $supplier->id)->sum('total_cost');
            $productNames = $supplier->products->pluck('name')->implode(', ');

            return [
                'supplier' => $supplier,
                'total' => $total,
                'products_count' => $supplier->products->count(),
                'product_names' => $productNames,
            ];
        });

        // --- Customer Reports ---
        $topCustomers = $orders->where('status', 'completed')
            ->groupBy('user_id')
            ->map(function ($userOrders) {
                $user = $userOrders->first()->user;
                $totalSpent = $userOrders->sum('total_amount');
                $totalItems = $userOrders->sum(fn($order) => $order->orderItem?->sum('quantity') ?? 0);
                $lastOrderDate = $userOrders->sortByDesc('created_at')->first()?->created_at ?? null;

                return [
                    'user'            => $user,
                    'spent'           => $totalSpent,
                    'orders_count'    => $userOrders->count(),
                    'items_count'     => $totalItems,
                    'last_order_date' => $lastOrderDate,
                ];
            })
            ->sortByDesc('spent')
            ->take(5);

        // --- Financial Reports ---
        $revenue = $totalRevenue;
        $expenses = $totalPurchases;
        $profit = $revenue - $expenses;

        // Compare with last month
            $lastMonthOrders = Order::where('status', 'completed')
                ->whereBetween('created_at', [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth()
                ])->get();

            $lastMonthRevenue  = $lastMonthOrders->sum('total_amount');
            $lastMonthPurchases = Purchase::whereBetween('purchase_date', [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth()
                ])->sum('total_cost');

            $lastMonthProfit = $lastMonthRevenue - $lastMonthPurchases;

            // --- Calculate percentage changes ---
            $revenueGrowth = $lastMonthRevenue > 0 
                ? (($revenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
                : 0;

            $expenseChange = $lastMonthPurchases > 0 
                ? (($expenses - $lastMonthPurchases) / $lastMonthPurchases) * 100 
                : 0;

            $profitMargin = $revenue > 0 
                ? ($profit / $revenue) * 100 
                : 0;
                
                // --- Inventory Turnover ---
            $averageInventory = Product::avg('stock');
            $inventoryTurnover = $averageInventory > 0 
                ? $totalPurchases / $averageInventory 
                : 0;

            $machine = Machine::all();
            $Products = Product::all();

        return view('admin.report.index', compact(
            'dateFrom',
            'dateTo',
            'status',
            'delivery',
            'totalRevenue',
            'ordersByStatus',
            'salesByDelivery',
            'topProducts',
            'lowStock',
            'stockLevels',
            'totalPurchases',
            'purchasesByStatus',
            'suppliers',
            'purchaseVolumePerSupplier',
            'topCustomers',
            'revenue',
            'expenses',
            'profit',
            'orders',
            'purchases',
            'ordersData',
            'profitMargin',
            'revenueGrowth',
            'expenseChange',
            'inventoryTurnover',
            'machine',
            'Products'
        ));
    }
}
