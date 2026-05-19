<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple authorization check (role should be 'admin')
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $stats = [
            'total_restaurants' => Restaurant::count(),
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'served')->sum('total_price'),
        ];

        $restaurants = Restaurant::with('owner')->latest()->get();
        $users = User::latest()->take(10)->get();
        $orders = Order::with(['restaurant', 'table', 'user', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('stats', 'restaurants', 'users', 'orders'));
    }

    public function toggleRestaurantStatus(Restaurant $restaurant)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $restaurant->update(['is_active' => !$restaurant->is_active]);

        return back()->with('success', 'Restaurant status updated!');
    }
}
