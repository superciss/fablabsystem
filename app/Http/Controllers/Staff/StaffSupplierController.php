<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class StaffSupplierController extends Controller
{
    /**
     * Show all purchases with remaining balance
     */
    public function index()
    {
        $purchases = Purchase::with('supplier', 'items.product')->latest()->get();

        // Add calculated remaining balance
        $purchases->transform(function ($purchase) {
            $purchase->remaining = match ($purchase->status) {
                'unpaid'  => $purchase->total_cost,
                'partial' => $purchase->total_cost / 2,
                'paid'    => 0,
            };
            return $purchase;
        });

        $suppliers = Supplier::all();
        $products  = Product::all();

        return view('staff.paysupply.index', compact('purchases', 'suppliers', 'products'));
    }

    /**
     * Store a new purchase and update stock
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'  => 'required|exists:suppliers,id',
            'purchase_date'=> 'required|date',
            'products.*'   => 'required|exists:product,id',
            'quantities.*' => 'required|integer|min:1',
            'costs.*'      => 'required|numeric|min:0',
        ]);

        $purchase = Purchase::create([
            'supplier_id'  => $request->supplier_id,
            'purchase_date'=> $request->purchase_date,
            'total_cost'   => 0,
            'status'       => 'unpaid',
        ]);

        $total = 0;

        foreach ($request->products as $index => $product_id) {
            $product   = Product::findOrFail($product_id);
            $quantity  = $request->quantities[$index];
            $cost      = $request->costs[$index];
            $subtotal  = $quantity * $cost;

            // Save purchase item
            $purchase->items()->create([
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'cost'       => $cost,
                'subtotal'   => $subtotal,
            ]);

            // Update product stock
            $product->stock += $quantity;
            $product->cost   = $cost;
            $product->save();

            $total += $subtotal;
        }

        $purchase->update(['total_cost' => $total]);

        return redirect()->back()->with('success', 'Purchase added successfully!');
    }

    /**
     * Update a purchase and re-sync stock
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id'  => 'required|exists:suppliers,id',
            'purchase_date'=> 'required|date',
            'status'       => 'required|in:unpaid,partial,paid',
            'products.*'   => 'required|exists:product,id',
            'quantities.*' => 'required|integer|min:1',
            'costs.*'      => 'required|numeric|min:0',
        ]);

        $purchase = Purchase::with('items')->findOrFail($id);

        // Rollback old stock and remove old items
        foreach ($purchase->items as $item) {
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
            $item->delete();
        }

        $total = 0;

        // Re-add items
        foreach ($request->products as $index => $product_id) {
            $product   = Product::findOrFail($product_id);
            $quantity  = $request->quantities[$index];
            $cost      = $request->costs[$index];
            $subtotal  = $quantity * $cost;

            $purchase->items()->create([
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'cost'       => $cost,
                'subtotal'   => $subtotal,
            ]);

            $product->stock += $quantity;
            $product->cost   = $cost;
            $product->save();

            $total += $subtotal;
        }

        // Update purchase record
        $purchase->update([
            'supplier_id'  => $request->supplier_id,
            'purchase_date'=> $request->purchase_date,
            'status'       => $request->status,
            'total_cost'   => $total,
        ]);

        return redirect()->back()->with('success', 'Purchase updated successfully!');
    }

    /**
     * Quick status update (unpaid, partial, paid)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:unpaid,partial,paid'
        ]);

        $purchase = Purchase::findOrFail($id);
        $purchase->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Purchase status updated!');
    }

    /**
     * Delete purchase and rollback stock
     */
    public function destroy($id)
    {
        $purchase = Purchase::with('items')->findOrFail($id);

        foreach ($purchase->items as $item) {
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
            $item->delete();
        }

        $purchase->delete();

          return redirect()->back()->with('success', 'Purchase Deleted Successful!');
    }
}
