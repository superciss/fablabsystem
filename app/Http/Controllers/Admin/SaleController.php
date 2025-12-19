<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentQuarter = ceil($currentMonth / 3);

        // 1. Quarterly Revenue (completed orders)
        $quarterRevenue = Order::whereYear('created_at', $currentYear)
            ->whereRaw('CEIL(MONTH(created_at)/3) = ?', [$currentQuarter])
            ->where('status', 'completed')
            ->sum('total_amount');

        // 2. Deals Closed (Month-To-Date)
        $dealsClosed = Order::whereMonth('created_at', $currentMonth)
            ->where('status', 'completed')
            ->count();

        // 3. Active Pipeline Value (pending or processing orders)
        $activePipeline = Order::whereIn('status', ['pending', 'processing'])
            ->sum('total_amount');

        // 4. Win Rate (Quarter-to-Date)
        $totalDeals = Order::whereYear('created_at', $currentYear)
            ->whereRaw('CEIL(MONTH(created_at)/3) = ?', [$currentQuarter])
            ->count();

        $wonDeals = Order::whereYear('created_at', $currentYear)
            ->whereRaw('CEIL(MONTH(created_at)/3) = ?', [$currentQuarter])
            ->where('status', 'completed')
            ->count();

        $winRate = $totalDeals ? round(($wonDeals / $totalDeals) * 100, 1) : 0;

        // 5. Recent Activity (latest 5 orders)
        $recentActivity = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 6. Top Performers (users by revenue)
        $topPerformers = User::select(
                'users.id',
                'users.name',
                DB::raw('COUNT(orders.id) as deals_closed'),
                DB::raw('SUM(orders.total_amount) as revenue')
            )
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', 'completed')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        // 7. Monthly Revenue for Chart
        $monthlyRevenue = Order::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->get()
            ->groupBy(fn($order) => $order->created_at->format('M'))
            ->map(fn($orders) => $orders->sum('total_amount'))
            ->toArray();

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $monthlyRevenue = collect($months)->mapWithKeys(function($m) use ($monthlyRevenue){
            return [$m => $monthlyRevenue[$m] ?? 0];
        })->toArray();

        // 8. Pipeline by Stage for Chart
        $pipelineStages = Order::whereYear('created_at', $currentYear)
            ->get()
            ->groupBy('status')
            ->map(fn($orders) => $orders->sum('total_amount'))
            ->toArray();

        $stages = ['pending','processing','completed','cancelled'];
        $pipelineStages = collect($stages)->mapWithKeys(function($s) use ($pipelineStages){
            return [$s => $pipelineStages[$s] ?? 0];
        })->toArray();

        // Available products for POS manual sale
        $products = Product::where('stock', '>', 0)->get();

        // Completed online orders for online payment modal
        $completedOrders = Order::with('user', 'orderitem.product')
            ->where('status', 'completed')
            ->get();

        return view('admin.sale.index', compact(
            'quarterRevenue',
            'dealsClosed',
            'activePipeline',
            'winRate',
            'recentActivity',
            'topPerformers',
            'monthlyRevenue',
            'pipelineStages',
            'products',
            'completedOrders'
        ));
    }
}
