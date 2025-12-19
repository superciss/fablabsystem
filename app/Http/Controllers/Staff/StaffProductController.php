<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class StaffProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('staff.product.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
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

        Product::create($data);

        return redirect()->route('staff.product.index')->with('success', 'Product added successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $data = $validated;

        // Save image as Base64 (if new uploaded)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $mime = $file->getClientMimeType();
            $data['image'] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        $product->update($data);

        return redirect()->route('staff.product.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('staff.product.index')->with('success', 'Product deleted successfully!');
    }
}
