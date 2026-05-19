<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurant;

        if (!$restaurant) {
            return redirect()->route('owner.onboarding');
        }

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->with(['table', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'revenue' => $orders->where('status', 'served')->sum('total_price'),
            'menu_items' => $restaurant->menuItems()->count(),
        ];

        \Illuminate\Support\Facades\Log::info('Owner Dashboard loaded', [
            'owner_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'orders_count' => $orders->count(),
            'stats' => $stats,
        ]);

        return view('owner.dashboard', compact('restaurant', 'orders', 'stats'));
    }

    public function getLiveOrders()
    {
        $restaurant = Auth::user()->restaurant;
        
        if (!$restaurant) {
            return response()->json([
                'orders' => [],
                'stats' => ['total_orders' => 0, 'revenue' => 0, 'menu_items' => 0],
                'error' => 'No restaurant found for user'
            ]);
        }
        
        $orders = Order::where('restaurant_id', $restaurant->id)
            ->with(['table', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'orders' => $orders,
            'stats' => [
                'total_orders' => $orders->count(),
                'revenue' => $orders->where('status', 'served')->sum('total_price'),
                'menu_items' => $restaurant->menuItems()->count(),
            ],
            'debug' => [
                'restaurant_id' => $restaurant->id,
                'orders_count' => $orders->count(),
                'first_order' => $orders->first()
            ]
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        if ($order->restaurant_id !== Auth::user()->restaurant->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,served,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated!');
    }
}
