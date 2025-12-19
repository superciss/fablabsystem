<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Custom;
use App\Models\Order;
use App\Models\CustomBackImage;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Texture;

use Illuminate\Support\Facades\Auth;

class CustomProductController extends Controller
{
    public function index()
    {
        $customizedProducts = Custom::where('user_id', Auth::id())
            ->with(['product', 'order'])
            ->latest()
            ->get();

        return view('customer.customproduct.index', compact('customizedProducts'));
    }

    
public function pay(Request $request)
{
    $request->validate([
        'customized_id' => 'required|exists:customized_products,id',
        'payment_type' => 'required|in:partial,full'
    ]);

    $custom = Custom::findOrFail($request->customized_id);

    $custom->payment_type = $request->payment_type;
    $custom->partial_amount = $request->payment_type === 'partial'
        ? $custom->total_price / 2
        : $custom->total_price;

    $custom->save();

    return back()->with('success', 'Payment successful.');
}


}


