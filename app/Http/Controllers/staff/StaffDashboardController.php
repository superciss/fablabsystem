<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
   public function index()
{
    $user = auth()->user();

    /** ──────── ORDERS OVERVIEW ──────── **/
    $ordersToday = Order::whereDate('created_at', Carbon::today())->count();
    $ordersPending = Order::where('status', 'pending')->count();
    $ordersCompleted = Order::where('status', 'completed')->count();
    $ordersCancelled = Order::where('status', 'cancelled')->count();

    // Yesterday counts (for trends %)
    $ordersYesterday = Order::whereDate('created_at', Carbon::yesterday())->count();
    $pendingYesterday = Order::where('status', 'pending')->whereDate('created_at', Carbon::yesterday())->count();
    $completedYesterday = Order::where('status', 'completed')->whereDate('created_at', Carbon::yesterday())->count();
    $cancelledYesterday = Order::where('status', 'cancelled')->whereDate('created_at', Carbon::yesterday())->count();

    $orderTrends = [
        'today'     => $ordersYesterday > 0 ? round((($ordersToday - $ordersYesterday) / $ordersYesterday) * 100, 2) : 100,
        'pending'   => $pendingYesterday > 0 ? round((($ordersPending - $pendingYesterday) / $pendingYesterday) * 100, 2) : 0,
        'completed' => $completedYesterday > 0 ? round((($ordersCompleted - $completedYesterday) / $completedYesterday) * 100, 2) : 0,
        'cancelled' => $cancelledYesterday > 0 ? round((($ordersCancelled - $cancelledYesterday) / $cancelledYesterday) * 100, 2) : 0,
    ];

    /** ──────── LOW STOCK PRODUCTS ──────── **/
    $lowStockCount = Product::where('stock', '<=', 10)->count();
    $lowStockProducts = Product::where('stock', '<=', 10)->get();

    /** ──────── RECENT ORDERS ──────── **/
    $recentOrders = Order::with('user')->latest()->take(5)->get();

    /** ──────── FINANCE KPIs ──────── **/
    $incomeToday = Order::whereDate('created_at', Carbon::today())
        ->where('status', 'completed')
        ->sum('total_amount');

    $totalCost = Purchase::sum('total_cost');

    // Ratio: Cost / Income
    $costToRevenueRatio = $incomeToday > 0 ? round(($totalCost / $incomeToday) * 100, 2) : 0;

    // Income Progress (vs daily target)
    $dailyTarget = 10000; // example: ₱10,000 target
    $incomeProgress = $dailyTarget > 0 ? min(round(($incomeToday / $dailyTarget) * 100, 2), 100) : 0;

    /** ──────── BEST SELLING PRODUCT ──────── **/
    $bestSelling = Product::withSum(['orderItems as total_sold' => function ($q) {
        $q->whereHas('order', function ($q2) {
            $q2->where('status', 'completed');
        });
    }], 'quantity')->orderByDesc('total_sold')->first();

    /** ──────── PROFIT TREND (MONTHLY) ──────── **/
    $months = collect(range(1,12))->map(fn($m) => Carbon::create()->month($m)->format('F'));

    $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total_sales')
        ->where('status', 'completed')
        ->groupBy('month')
        ->pluck('total_sales', 'month');

    $monthlyPurchases = Purchase::selectRaw('MONTH(created_at) as month, SUM(total_cost) as total_cost')
        ->groupBy('month')
        ->pluck('total_cost', 'month');

    $profitMonths = [];
    $profitAmounts = [];
    $revenueAmounts = [];
    $costAmounts = [];

    foreach($months as $index => $monthName) {
        $monthNumber = $index + 1;
        $revenue = $monthlySales[$monthNumber] ?? 0;
        $cost = $monthlyPurchases[$monthNumber] ?? 0;
        $profit = $revenue - $cost;

        $profitMonths[] = $monthName;
        $profitAmounts[] = $profit;
        $revenueAmounts[] = $revenue;
        $costAmounts[] = $cost;
    }

    return view('staff.dashboard', compact(
        'ordersToday',
        'ordersPending',
        'ordersCompleted',
        'ordersCancelled',
        'lowStockCount',
        'lowStockProducts',
        'recentOrders',
        'incomeToday',
        'totalCost',
        'bestSelling',
        'profitMonths',
        'profitAmounts',
        'revenueAmounts',
        'costAmounts',
        'incomeProgress',
        'costToRevenueRatio',
        'orderTrends'
    ));
}

}
