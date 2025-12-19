<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Information;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
   public function profile()
    {
        $user = Auth::user()->load('userInformation'); // eager load info relation
        return view('customer.profile.viewprofile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:15',
            'degree' => 'nullable|string',
            'year' => 'nullable|integer',
            'section' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        // get basic data first
        $data = $request->only(['fullname','address','contact_number','degree','year','section','gender']);

        // ✅ Convert uploaded photo to base64 and store directly in DB
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            $mime = $image->getClientMimeType();
            $data['photo'] = "data:$mime;base64,$imageData";
        }

        Information::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return back()->with('success', 'Profile updated successfully!');
    }

}
