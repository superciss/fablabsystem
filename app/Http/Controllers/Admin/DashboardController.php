<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total users
        $totalUsers = User::count();

        // Total inventory (sum of product stock)
        $totalInventory = Product::sum('stock');

        // Total sales
        $today = Carbon::today();
        $totalSaleDay = Order::whereDate('created_at', $today)->sum('total_amount');
        $totalSaleWeek = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total_amount');
        $totalSaleMonth = Order::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        $totalSaleYear = Order::whereYear('created_at', Carbon::now()->year)->sum('total_amount');

        // Recent orders (last 5)
        $recentOrders = Order::with('user')->latest()->take(5)->get();

            // Low stock alert (e.g., stock <= 10)
    $lowStockProducts = Product::whereNotNull('low_stock_threshold')
        ->whereColumn('stock', '<=', 'low_stock_threshold')
        ->get();

    // Products with 0 stock
    $lowStockCount = Product::where('stock', '<=', 0)->get();

        // Monthly revenue for chart (last 12 months)
        $monthlyRevenue = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Ensure all 12 months exist (fill missing months with 0)
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $monthlyRevenue[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalInventory',
            'totalSaleDay',
            'totalSaleWeek',
            'totalSaleMonth',
            'totalSaleYear',
            'recentOrders',
            'lowStockCount',
            'lowStockProducts',
            'months' // pass $months to view
        ));
    }
}
