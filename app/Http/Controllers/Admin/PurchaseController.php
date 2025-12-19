<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier','items.product')->get();
        $suppliers = Supplier::all();
        $products  = Product::all();

          // Calculate balance for each purchase
        foreach($purchases as $purchase){
            if($purchase->status == 'unpaid'){
                $purchase->balance = $purchase->total_cost;
            } elseif($purchase->status == 'partial'){
                $purchase->balance = $purchase->total_cost / 2;
            } else { // paid
                $purchase->balance = 0;
            }
        }
        
        return view('admin.purchase.index', compact('purchases','suppliers','products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_date'  => 'required|date',
            'status'         => 'required|in:unpaid,partial,paid',
            'product_id.*'   => 'nullable|exists:product,id',
            'quantity.*'     => 'nullable|integer|min:1',
            'cost.*'         => 'nullable|numeric|min:0',
        ]);

        $purchase = Purchase::create([
            'supplier_id'   => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'status'        => $request->status,
            'total_cost'    => 0,
        ]);

        $total = 0;
        foreach ($request->product_id as $key => $productId) {
            if ($productId && $request->quantity[$key] && $request->cost[$key]) {
                $quantity = $request->quantity[$key];
                $cost     = $request->cost[$key];
                $subtotal = $quantity * $cost;
                $total   += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $productId,
                    'quantity'    => $quantity,
                    'cost'        => $cost,
                ]);

                // Update product cost & stock
                $product = Product::find($productId);
                if($product) {
                    $product->update([
                        'cost'  => $cost,
                        'stock' => $product->stock + $quantity,
                    ]);
                }
            }
        }

        $purchase->update(['total_cost' => $total]);

        return redirect()->back()->with('success', 'Purchase created successfully!');
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_date'  => 'required|date',
            'status'         => 'required|in:unpaid,partial,paid',
            'product_id.*'   => 'nullable|exists:product,id',
            'quantity.*'     => 'nullable|integer|min:1',
            'cost.*'         => 'nullable|numeric|min:0',
        ]);

        // Delete old items
        $purchase->items()->delete();

        $total = 0;
        foreach ($request->product_id as $key => $productId) {
            if ($productId && $request->quantity[$key] && $request->cost[$key]) {
                $quantity = $request->quantity[$key];
                $cost     = $request->cost[$key];
                $subtotal = $quantity * $cost;
                $total   += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $productId,
                    'quantity'    => $quantity,
                    'cost'        => $cost,
                ]);

                $product = Product::find($productId);
                if($product) {
                    $product->update([
                        'cost'  => $cost,
                        'stock' => $product->stock + $quantity,
                    ]);
                }
            }
        }

        $purchase->update([
            'supplier_id'   => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'status'        => $request->status,
            'total_cost'    => $total,
        ]);

        return redirect()->back()->with('success', 'Purchase updated successfully!');
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();
        return redirect()->back()->with('success', 'Purchase deleted!');
    }
}
