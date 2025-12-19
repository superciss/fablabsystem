<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\PersonalDesign;
use Illuminate\Support\Facades\Auth;

class PersonalDesignController extends Controller
{
    public function index()
    {
        $designs = PersonalDesign::where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('customer.personaldesign.index', compact('designs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string',
            'total_price' => 'nullable|numeric',
            'image_design' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageBase64 = null;
        if ($request->hasFile('image_design')) {
            $image = $request->file('image_design');
            $imageBase64 = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image));
        }

        PersonalDesign::create([
            'user_id' => Auth::id(),
            'description' => $request->description,
            'total_price' => $request->total_price,
            'image_design' => $imageBase64,
        ]);

        return redirect()->back()->with('success', 'Design added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'nullable|string',
            'total_price' => 'nullable|numeric',
            'image_design' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $design = PersonalDesign::findOrFail($id);

        if ($request->hasFile('image_design')) {
            $image = $request->file('image_design');
            $design->image_design = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image));
        }

        $design->description = $request->description;
        $design->total_price = $request->total_price;
        $design->save();

        return redirect()->back()->with('success', 'Design updated successfully.');
    }

    public function destroy($id)
    {
        $design = PersonalDesign::findOrFail($id);
        $design->delete();

        return redirect()->back()->with('success', 'Design deleted successfully.');
    }

}
