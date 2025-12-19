<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Texture;

class StaffTextureController extends Controller
{
    public function index()
    {
        $textures = Texture::latest()->get();
        return view('staff.textures.index', compact('textures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price'    => 'nullable|numeric|min:0',
            'size'     => 'nullable|string|max:100',
        ]);

        $imageBase64 = base64_encode(file_get_contents($request->file('image')->getRealPath()));

        Texture::create([
            'name'     => $request->name,
            'category' => $request->category,
            'image'    => $imageBase64,
            'price'    => $request->price,
            'size'     => $request->size,
        ]);

        return redirect()->route('staff.textures.index')->with('success', 'Texture added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price'    => 'nullable|numeric|min:0',
            'size'     => 'nullable|string|max:100',
        ]);

        $texture = Texture::findOrFail($id);
        
        $data = [
            'name'     => $request->name,
            'category' => $request->category,
            'price'    => $request->price,
            'size'     => $request->size,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = base64_encode(file_get_contents($request->file('image')->getRealPath()));
        }

        $texture->update($data);

        return redirect()->route('staff.textures.index')->with('success', 'Texture updated successfully!');
    }

    public function destroy($id)
    {
        Texture::findOrFail($id)->delete();
        return redirect()->route('staff.textures.index')->with('success', 'Texture deleted successfully!');
    }
}