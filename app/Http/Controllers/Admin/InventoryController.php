<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // --- Stock per category ---
        $rawStock       = Product::whereHas('category', fn($q) => $q->where('name', 'Raw Material'))->sum('stock');
        $wholesaleStock = Product::whereHas('category', fn($q) => $q->where('name', 'Wholesale'))->sum('stock');
        $finishedStock  = Product::whereHas('category', fn($q) => $q->where('name', 'Finished Product'))->sum('stock');

        // --- Low stock counts per category ---
        $lowStockRaw       = Product::whereHas('category', fn($q) => $q->where('name', 'Raw Material'))->where('stock', '<', 5)->count();
        $lowStockWholesale = Product::whereHas('category', fn($q) => $q->where('name', 'Wholesale'))->where('stock', '<', 5)->count();
        $lowStockFinished  = Product::whereHas('category', fn($q) => $q->where('name', 'Finished Product'))->where('stock', '<', 5)->count();

        // --- Overall inventory stats ---
        $totalStock      = Product::sum('stock');
        $lowStockCount   = Product::where('stock', '<', 5)->count();
        $outOfStockCount = Product::where('stock', 0)->count();

        // --- Total values (computed manually) ---
        $products = Product::all();
        $totalRetailValue = $products->sum(fn($p) => $p->price * $p->stock);
        $totalCostValue   = $products->sum(fn($p) => $p->cost * $p->stock);

        // --- Profit (based on sold items, not current stock) ---
        $sales = OrderItem::with('product')->get();
        $salesRevenue = 0;
        $totalCost    = 0;

        foreach ($sales as $item) {
            if ($item->product) {
                $salesRevenue += $item->price * $item->quantity; // selling price
                $totalCost    += $item->product->cost * $item->quantity; // purchase cost
            }
        }

        $profit = $salesRevenue - $totalCost;
        $grossMargin = $salesRevenue > 0 ? ($profit / $salesRevenue) * 100 : 0;

        // --- Recently updated products (with category + stock prediction) ---
        $recentProducts = Product::with('category')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($product) {
                // Compute average daily sales
                $sales = OrderItem::where('product_id', $product->id)->get();
                $totalSold = $sales->sum('quantity');

                // Days since first sale
                $firstSale = $sales->min('created_at');
                $days = $firstSale ? max(1, now()->diffInDays($firstSale)) : 1;

                $avgDailySales = $days > 0 ? $totalSold / $days : 0;

                // ✅ Refined stock-out prediction
                if ($product->stock <= 0) {
                    $estimatedDays = 'Out';
                } elseif ($avgDailySales <= 0) {
                    $estimatedDays = 'Stable';
                } else {
                    $estimatedDays = round($product->stock / $avgDailySales, 1);
                }

                $product->avgDailySales = $avgDailySales;
                $product->estimatedDays = $estimatedDays;

                return $product;
            });

        return view('admin.inventory', compact(
            'totalStock',
            'lowStockCount',
            'outOfStockCount',
            'totalRetailValue',
            'totalCostValue',
            'profit',
            'salesRevenue',
            'grossMargin',
            'recentProducts',
            'rawStock', 'wholesaleStock', 'finishedStock',
            'lowStockRaw', 'lowStockWholesale', 'lowStockFinished'
        ));
    }
}
