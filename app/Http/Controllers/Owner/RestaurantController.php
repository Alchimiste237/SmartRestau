<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    public function edit()
    {
        $restaurant = Auth::user()->restaurant;
        return view('owner.restaurant.edit', compact('restaurant'));
    }

    public function update(Request $request)
    {
        $restaurant = Auth::user()->restaurant;

        $request->validate([
            'name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'local_network_url' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'cover' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'business_type', 'location', 'contact', 'local_network_url']);

        if ($request->hasFile('logo')) {
            if ($restaurant->logo_path) {
                Storage::disk('public')->delete($restaurant->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('cover')) {
            if ($restaurant->cover_path) {
                Storage::disk('public')->delete($restaurant->cover_path);
            }
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $restaurant->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}
