<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;

class RawMaterialController extends Controller
{
    public function index()
{
    $products = Product::with('category')
        ->whereHas('category', function($query) {
            $query->whereIn('name', ['Raw Material']);
        })
        ->withSum('orderItems as consumed_units', 'quantity')
        ->latest()
        ->get();

    $categories = Category::where('name', 'Raw Material')->get();

    return view('admin.materials.index', compact('products', 'categories'));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'category_id'   => 'required|exists:categories,id',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'pro_category'   => 'nullable|string',
            'is_customizable' => 'nullable|boolean', 
        ]);

        $data = $validated;

        // 🔥 Auto-generate SKU
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $nextId = $lastProduct ? $lastProduct->id + 1 : 1;
        $data['sku'] = 'PRD-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Save image as Base64
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $mime = $file->getClientMimeType();
            $data['image'] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        $data['unit'] = 'pcs'; // default unit

        // ✅ Ensure default if not set
        $data['is_customizable'] = $request->has('is_customizable') ? 1 : 0;

        Product::create($data);

        return redirect()->route('admin.materials.index')->with('success', 'Raw Material added successfully!');
    }

    public function update(Request $request, Product $prod)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'category_id'   => 'required|exists:categories,id',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'pro_category'   => 'nullable|string',
            'is_customizable' => 'nullable|boolean', 
        ]);

        $data = $validated;

        // Save image as Base64 (if new uploaded)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $mime = $file->getClientMimeType();
            $data['image'] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        // ✅ Ensure default if not set
        $data['is_customizable'] = $request->has('is_customizable') ? 1 : 0;

        $prod->update($data);

        return redirect()->route('admin.materials.index')->with('success', 'Raw MAterial updated successfully!');
    }

    public function destroy(Product $prod)
    {
        $prod->delete();
        return redirect()->route('admin.materials.index')->with('success', 'Raw Material deleted successfully!');
    }

    
        public function count()
{
    $low = Product::whereNotNull('low_stock_threshold')
        ->whereColumn('stock', '<=', 'low_stock_threshold')
        ->count();

    return response()->json(['count' => $low]);
}


}
