<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function showOnboarding()
    {
        // If they already have a restaurant, go to dashboard
        if (Auth::user()->restaurant) {
            return redirect()->route('owner.dashboard');
        }

        return view('owner.onboarding');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'opening_hours' => 'nullable|array',
        ]);

        $restaurant = Restaurant::create([
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'business_type' => $request->business_type,
            'location' => $request->location,
            'contact' => $request->contact,
            'opening_hours' => $request->opening_hours ?? [
                'monday' => '08:00 - 22:00',
                'tuesday' => '08:00 - 22:00',
                'wednesday' => '08:00 - 22:00',
                'thursday' => '08:00 - 22:00',
                'friday' => '08:00 - 22:00',
                'saturday' => '08:00 - 22:00',
                'sunday' => '08:00 - 22:00',
            ],
            'is_active' => true,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Restaurant setup complete!');
    }
}
