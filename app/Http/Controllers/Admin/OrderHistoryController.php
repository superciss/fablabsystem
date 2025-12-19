<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index()
    {
        // History = completed + cancelled + paid (customize mo)
        $orders = Order::with('user', 'orderitem.product')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.history.index', compact('orders'));
    }
}
