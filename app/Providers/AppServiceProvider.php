<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot()
{
View::composer('*', function ($view) {
    $lowStockProducts = Product::whereNotNull('low_stock_threshold')
        ->whereColumn('stock', '<=', 'low_stock_threshold')
        ->get();

    $lowStockCount = $lowStockProducts->count();

    $view->with(compact('lowStockProducts', 'lowStockCount'));
});

    
    // View::composer('*', function ($view) {
    //     $lowStockCount = Product::where('stock', '<=', 20)->count();
    //     $view->with('lowStockCount', $lowStockCount);
    // });
}
}

