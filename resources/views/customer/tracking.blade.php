<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Track Your Order - {{ $order->restaurant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="max-w-md mx-auto min-h-screen flex flex-col">
        <header class="bg-white shadow-sm p-4 text-center">
            <h1 class="text-xl font-bold">Order Tracking</h1>
            <p class="text-xs text-orange-600 font-bold">Table {{ $order->table->table_number }} - #{{ substr($order->id, 0, 8) }}</p>
        </header>

        <main class="flex-grow p-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center space-y-8">
                <!-- Status Icon -->
                <div class="relative">
                    <div class="w-32 h-32 bg-orange-100 rounded-full mx-auto flex items-center justify-center">
                        @if($order->status === 'pending')
                            <svg class="w-16 h-16 text-orange-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @elseif($order->status === 'preparing')
                            <svg class="w-16 h-16 text-orange-600 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        @elseif($order->status === 'served')
                            <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @endif
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-black uppercase text-gray-900 tracking-tight">
                        @if($order->status === 'pending')
                            Order Received
                        @elseif($order->status === 'confirmed')
                            Confirmed
                        @elseif($order->status === 'preparing')
                            In the Kitchen
                        @elseif($order->status === 'served')
                            Enjoy your meal!
                        @endif
                    </h2>
                    <p class="text-gray-500 mt-2">
                        @if($order->status === 'pending')
                            We've received your order and are waiting for the staff to confirm it.
                        @elseif($order->status === 'preparing')
                            Our chefs are currently preparing your delicious meal.
                        @elseif($order->status === 'served')
                            Your food has been served. Thank you for dining with us!
                        @endif
                    </p>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 text-left">Order Summary</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    <span class="font-bold text-gray-900">{{ $item->quantity }}x</span> {{ $item->menuItem->name }}
                                </span>
                                <span class="font-bold text-gray-900">{{ number_format($item->unit_price * $item->quantity, 0, ',', ' ') }} FCFA</span>
                            </div>
                        @endforeach
                        <div class="flex justify-between text-lg font-black border-t border-gray-50 pt-3">
                            <span>Total</span>
                            <span>{{ number_format($order->total_price, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Need help? Call a waiter to your table.</p>
                <button class="mt-4 bg-white border border-gray-300 text-gray-700 px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-50">
                    Call Waiter
                </button>
            </div>
        </main>

        <footer class="p-8 text-center text-xs text-gray-400">
            Powered by SmartRestaurant OS
        </footer>
    </div>

    <style>
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }
    </style>

    <script>
        // Simple polling to refresh the page every 30 seconds to update status
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>
