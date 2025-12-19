<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    // public function index()
    // {
    //     $products = Product::with(['ratings'])->where('stock', '>', 0)->latest()->get();
    //     return view('customer.dashboard', compact('products'));
    // }

//     public function index()
// {
//     $products = Product::with(['ratings'])->latest()->get();
//     return view('customer.dashboard', compact('products'));
// }


public function index()
{
    $products = Product::with(['ratings', 'category'])
        ->whereHas('category', function ($query) {
            $query->whereIn('name', ['Wholesale', 'Finished Product']);
        })
        ->latest()
        ->get();

    // Only include categories we want to display (no Raw Material)
    $categories = \App\Models\Category::whereIn('name', ['Wholesale', 'Finished Product'])->get();

     $topProducts = Product::with(['ratings'])
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(5)
            ->get();



    return view('customer.dashboard', compact('products', 'categories', 'topProducts'));
}



    public function indexview($id)
{
    $product = Product::with(['ratings.user'])->findOrFail($id);

    $totalSold = \App\Models\OrderItem::where('product_id', $id)->sum('quantity');

    return view('customer.indexview', compact('product', 'totalSold'));
}



public function storeRating(Request $request, $id)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
        'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048', // file validation
    ]);

    // ✅ Check if the user has purchased this product
    $hasPurchased = \App\Models\OrderItem::where('product_id', $id)
        ->whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())
                  ->where('status', 'completed');
        })
        ->exists();

    if (!$hasPurchased) {
        return back()->with('error', 'You can only rate this product after purchasing it.');
    }

    // ✅ Handle image upload (convert to base64)
    $base64Image = null;
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $mime = $file->getClientMimeType();
        $base64Image = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
    }

    // ✅ Save or update rating
    Rating::updateOrCreate(
        [
            'product_id' => $id,
            'user_id'    => Auth::id(),
        ],
        [
            'rating'  => $request->rating,
            'comment' => $request->comment,
            'image'   => $base64Image, // dito na siya masisave
        ]
    );

    return back()->with('success', 'Thanks for rating this product!');
}



    // public function storeRating(Request $request, $id)
    // {
    //     $request->validate([
    //         'rating' => 'required|integer|min:1|max:5',
    //         'comment' => 'nullable|string|max:500',
    //     ]);

    //     Rating::updateOrCreate(
    //         ['product_id' => $id, 'user_id' => Auth::id()],
    //         ['rating' => $request->rating, 'comment' => $request->comment]
    //     );

    //     return back()->with('success', 'Thanks for rating this product!');
    // }
}
