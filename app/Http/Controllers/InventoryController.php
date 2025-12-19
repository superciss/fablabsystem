<?php
namespace App\Http\Controllers;

use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Inventory::all();
        return view('staff.inventory', compact('items'));
    }
}