<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\Machine;
use Illuminate\Http\Request;

class StaffInventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('category','supplier')->get();
       $orders = Order::with(['user','orderitem'])->latest()->get();
       $machine = Machine::with(['suppliers'])->latest()->get();
        $purchases = Purchase::with('supplier')->latest()->get();
        $logs = Inventory::with('product')->latest()->get();

        return view('staff.inventories.index', compact('products','orders','purchases','logs', 'machine'));
    }
}
