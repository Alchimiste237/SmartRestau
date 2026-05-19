<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function show(Restaurant $restaurant, RestaurantTable $table)
    {
        // Ensure table belongs to restaurant
        if ($table->restaurant_id !== $restaurant->id) {
            abort(404);
        }

        $restaurant->load(['menuCategories.items' => function($query) {
            $query->where('is_available', true);
        }]);

        return view('customer.menu', compact('restaurant', 'table'));
    }

    public function processOrder(Request $request, Restaurant $restaurant, RestaurantTable $table)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthenticated order attempt', [
                'restaurant_id' => $restaurant->id,
                'table_id' => $table->id,
                'session_id' => session()->getId(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Please sign up to continue'
            ], 401);
        }

        Log::info('Order processing started', [
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
        ]);

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:momo,cash',
        ]);

        // If mobile payment, simulate the payment process
        if ($request->payment_method === 'momo') {
            return response()->json([
                'success' => true,
                'message' => 'Proceed to payment simulation',
                'items' => $request->items,
                'payment_method' => $request->payment_method,
                'restaurant_id' => $restaurant->id,
                'table_id' => $table->id,
                'needs_payment_simulation' => true
            ]);
        }

        // For cash payment, create the order immediately
        return $this->createOrder($request, $restaurant, $table, 'cash');
    }

    public function simulatePayment(Request $request, Restaurant $restaurant, RestaurantTable $table)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthenticated payment simulation', [
                'restaurant_id' => $restaurant->id,
                'table_id' => $table->id,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please sign up again.'
            ], 401);
        }

        Log::info('Payment simulation started', [
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
        ]);

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:momo,cash',
            'payment_details' => 'required|array',
            'payment_details.provider' => 'required|string|in:mtn,orange',
            'payment_details.phone' => 'required|string|regex:/^[0-9]{9,15}$/',
        ]);

        // Simulate payment processing with a transaction record
        return DB::transaction(function () use ($request, $restaurant, $table) {
            // Create a temporary payment record or log
            $paymentData = [
                'provider' => $request->input('payment_details.provider'),
                'phone' => $request->input('payment_details.phone'),
                'status' => 'successful',
                'transaction_id' => 'SIM_' . uniqid(),
                'timestamp' => now()->toDateTimeString()
            ];

            Log::info('Payment details captured', [
                'provider' => $paymentData['provider'],
                'phone' => $paymentData['phone'],
                'transaction_id' => $paymentData['transaction_id'],
            ]);

            // Create the order with payment details
            return $this->createOrder($request, $restaurant, $table, 'momo', $paymentData);
        });
    }

    private function createOrder(Request $request, Restaurant $restaurant, RestaurantTable $table, $paymentMethod, $paymentData = null)
    {
        try {
            return DB::transaction(function () use ($request, $restaurant, $table, $paymentMethod, $paymentData) {
                // Verify restaurant and table relationship
                if ($table->restaurant_id !== $restaurant->id) {
                    throw new \Exception('Table does not belong to this restaurant');
                }

                Log::info('Creating order', [
                    'restaurant_id' => $restaurant->id,
                    'table_id' => $table->id,
                    'user_id' => Auth::id(),
                    'payment_method' => $paymentMethod,
                    'items_count' => count($request->items),
                ]);

                $order = Order::create([
                    'restaurant_id' => $restaurant->id,
                    'table_id' => $table->id,
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                    'payment_method' => $paymentMethod,
                    'payment_details' => $paymentData,
                    'total_price' => 0,
                ]);

                if (!$order) {
                    throw new \Exception('Failed to create order');
                }

                Log::info('Order created', ['order_id' => $order->id]);

                $totalPrice = 0;
                foreach ($request->items as $itemData) {
                    $menuItem = \App\Models\MenuItem::find($itemData['id']);
                    
                    if (!$menuItem) {
                        throw new \Exception('Menu item not found: ' . $itemData['id']);
                    }
                    
                    $unitPrice = $menuItem->price;
                    $subtotal = $unitPrice * $itemData['quantity'];
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $unitPrice,
                        'notes' => $itemData['notes'] ?? null,
                    ]);

                    $totalPrice += $subtotal;
                }

                $order->update(['total_price' => $totalPrice]);

                Log::info('Order completed', [
                    'order_id' => $order->id,
                    'total_price' => $totalPrice,
                    'items_count' => count($request->items),
                ]);

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect' => route('customer.order-tracking', $order->id),
                    'message' => 'Order placed successfully!'
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage(), [
                'restaurant_id' => $restaurant->id,
                'table_id' => $table->id,
                'user_id' => Auth::id(),
                'payment_method' => $paymentMethod,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Order creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trackOrder(Order $order)
    {
        $order->load(['restaurant', 'table', 'items.menuItem']);
        return view('customer.tracking', compact('order'));
    }
}
