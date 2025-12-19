<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalDesign;

class StaffCustomDesignController extends Controller
{
    public function index()
    {
        $designs = PersonalDesign::with('user')->get();
        return view('staff.customdesign.index', compact('designs'));
    }

    public function approve($id)
    {
        $design = PersonalDesign::findOrFail($id);
        $design->approved = 1;
        $design->save();

        return redirect()->back()->with('success', 'Design approved successfully.');
    }

    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'total_price' => 'required|numeric'
        ]);

        $design = PersonalDesign::findOrFail($id);
        $design->total_price = $request->total_price;
        $design->save();

        return redirect()->back()->with('success', 'Price updated successfully.');
    }

    public function destroy($id)
    {
        $design = PersonalDesign::findOrFail($id);
        $design->delete();

        return redirect()->back()->with('success', 'Design deleted successfully.');
    }
}
