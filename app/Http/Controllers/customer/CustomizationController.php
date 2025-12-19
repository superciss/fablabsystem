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

class CustomizationController extends Controller
{
      public function customizeditem()
    {
       // $customizations = Custom::where('approved', true)->where( 'user_id', Auth::id())->latest()->get();
         $customizations = Custom::where( 'user_id', Auth::id())->latest()->get();
        return view('customer.customized.index', compact('customizations'));
    }


// public function getPdfData($id)
// {
//     $custom = Custom::with('order')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();

//     return response()->json([
//         'customer_name' => Auth::user()->name ?? 'Customer',
//         'partial_amount' => $custom->partial_amount,
//         'payment_type' => $custom->payment_type,
//         'order' => $custom->order ? [
//             'estimate_date' => $custom->order->estimate_date ? $custom->order->estimate_date->format('M d, Y') : null,
//             'status' => $custom->order->status,
//             'delivery_type' => $custom->order->delivery_type,
//         ] : null
//     ]);
// }




   public function create()
    {
        return view('customer.custom.create');
    }
    
public function customize($id)
{
    $product = Product::findOrFail($id);
     $textures = Texture::all();

    if (!$product->is_customizable) {
        return redirect()->back()->with('error', 'This product is not customizable.');
    }

    // Use last word of the product name for the blade file
    $parts = explode(' ', $product->name);
    $viewName = strtolower(end($parts)); // "Aluminum Sipper Water Bottle" -> "bottle"

    $viewPath = "customer.custom.$viewName";

    if (!view()->exists($viewPath)) {
        abort(404, "Customize view not found for product: {$product->name}");
    }

    return view($viewPath, compact('product', 'textures'));
}




    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'product_id'   => 'required|exists:products,id',
    //         'front_image'        => 'nullable|string', // base64 PNG string
    //         'back_image'   => 'nullable|string', // ✅ back image (optional)
    //         'description'  => 'nullable|string|max:500',
    //         'total_price' => 'required|numeric|min:0',
    //     ]);

    //     $custom = Custom::create([
    //         'user_id'     => Auth::id(),
    //         'product_id'  => $request->product_id,
    //         'front_image'       => $request->front_image,
    //         'back_image'  => $request->back_image, // ✅ save back image
    //         'description' => $request->description,
    //          'total_price' => $request->total_price,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Customized product saved!',
    //         'data'    => $custom,
    //     ]);
    // }


   public function store(Request $request)

   
{
    try {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'front_image' => 'nullable|string',
            'back_image' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'total_price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $finalPrice = $request->total_price; 
        // $finalPrice = $product->price + $request->total_price;

        $custom = Custom::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'front_image' => $request->front_image,
            'back_image' => $request->back_image,
            'description' => $request->description,
            'total_price' => $finalPrice,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'data' => $custom,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
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





    public function store1(Request $request, $customizedProductId)
{
    $request->validate([
        'back_img' => 'required|string', // 
    ]);

    $custom = Custom::findOrFail($customizedProductId);

    $backImage = $custom->backImage()->updateOrCreate(
        ['customized_product_id' => $custom->id],
        ['back_img' => $request->back_img] 
    );

    return response()->json([
        'success' => true,
        'message' => 'Back image saved!',
        'data'    => $backImage,
    ]);
}




   public function calculatePrice(Request $request)
{
    $total = 0;

    // base product price (optional kung may base price ka)
    if ($request->product_id) {
        $product = Product::find($request->product_id);
        if ($product) {
            $total += $product->price;
        }
    }

    // add material option
    if ($request->option_id) {
        $option = ProductOption::find($request->option_id);
        if ($option) {
            $total += $option->extra_price;
        }
    }

    // ✅ handle multiple textures (including duplicates)
    if ($request->has('texture_ids') && is_array($request->texture_ids)) {
        // Count how many times each texture ID appears
        $textureCounts = array_count_values($request->texture_ids);

        // Get only unique texture records
        $textures = Texture::whereIn('id', array_keys($textureCounts))->get();

        // Multiply price by the count of each texture
        foreach ($textures as $texture) {
            $count = $textureCounts[$texture->id];
            $total += $texture->price * $count;
        }
    }
    // backward compatibility (kung single texture lang ang pinasa)
    elseif ($request->texture_id) {
        $texture = Texture::find($request->texture_id);
        if ($texture && $texture->price) {
            $total += $texture->price;
        }
    }

    return response()->json([
        'success' => true,
        'total' => number_format($total, 2)
    ]);
}



}
