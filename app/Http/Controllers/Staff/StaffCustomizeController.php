<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Custom;
use App\Models\CustomBackImage;
use App\Models\Product;

class StaffCustomizeController extends Controller
{
    public function index()
    {
        // Load customizations with related product
         $customizations = Custom::with('product', 'backImage')->latest()->get();

        return view('staff.customize.index', compact('customizations'));
    }

    public function destroy($id)
    {
        Custom::findOrFail($id)->delete();
        return redirect()->route('staff.customize.index')->with('success', 'Customize deleted successfully!');
    }
}
