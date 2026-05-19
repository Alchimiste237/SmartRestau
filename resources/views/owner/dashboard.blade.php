@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8" x-data="dashboardPolling()">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">{{ $restaurant->name }} Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('owner.categories.index') }}" class="bg-white border border-gray-300 px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Manage Categories</a>
            <a href="{{ route('owner.items.index') }}" class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-700">Manage Menu Items</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-400 p-4">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-10">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.total_orders">{{ $stats['total_orders'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="formatPrice(stats.revenue)">{{ number_format($stats['revenue'], 0, ',', ' ') }} FCFA</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Menu Items</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.menu_items">{{ $stats['menu_items'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Live Orders -->
        <div class="lg:col-span-2">
<h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                Live Orders
                <span class="ml-3 px-2 py-1 bg-red-100 text-red-600 text-xs font-black rounded-full animate-pulse">LIVE</span>
            </h2>
            <button @click="fetchOrders()" class="ml-4 px-4 py-2 bg-orange-600 text-white rounded-lg font-bold text-sm hover:bg-orange-700 transition flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
            <button @click="playNotificationSound()" class="ml-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold text-sm hover:bg-gray-200 transition flex items-center shadow-sm border border-gray-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
                Test Sound
            </button>
            
            <div class="space-y-6">
                <template x-for="order in orders" :key="order.id">
                    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden transition transform hover:scale-[1.01]">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="text-xs font-bold text-orange-600 uppercase tracking-widest" x-text="order.table ? 'Table ' + order.table.table_number : 'Unknown Table'"></span>
                                    <h3 class="text-lg font-bold text-gray-900" x-text="'#' + (order.id ? order.id.substring(0, 8) : '...')"></h3>
                                    <p class="text-xs text-gray-500" x-text="formatDate(order.created_at)"></p>
                                </div>
                                <div class="text-right">
                                    <span :class="getStatusClass(order.status)" class="px-3 py-1 rounded-full text-xs font-black uppercase" x-text="order.status"></span>
                                    <p class="mt-2 font-black text-gray-900" x-text="formatPrice(order.total_price)"></p>
                                </div>
                            </div>

                            <div class="border-t border-gray-50 py-4">
                                <ul class="space-y-2">
                                    <template x-for="item in (order.items || [])" :key="item.id">
                                        <li class="flex justify-between text-sm">
                                            <span class="text-gray-700">
                                                <span class="font-bold" x-text="item.quantity + 'x'"></span> 
                                                <span x-text="item.menu_item ? item.menu_item.name : 'Deleted Item'"></span>
                                            </span>
                                            <span x-show="item.notes" class="text-xs italic text-gray-400" x-text="'&quot;' + item.notes + '&quot;'"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <div class="border-t border-gray-50 pt-4 flex space-x-3">
                                <template x-if="order.status === 'pending'">
                                    <form :action="'/owner/orders/' + order.id + '/status'" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="preparing">
                                        <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-orange-700">Start Preparing</button>
                                    </form>
                                </template>
                                <template x-if="order.status === 'preparing'">
                                    <form :action="'/owner/orders/' + order.id + '/status'" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="served">
                                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-green-700">Mark as Served</button>
                                    </form>
                                </template>
                                <form :action="'/owner/orders/' + order.id + '/status'" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="px-4 py-2 border border-gray-200 text-gray-400 hover:text-red-600 rounded-lg text-sm font-bold">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="orders.length === 0" class="py-20 bg-white rounded-xl border border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-500">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <p class="text-lg font-medium text-gray-900 mb-1">No orders yet</p>
                    <p class="text-sm mb-8">They will appear here in real-time as soon as a customer orders.</p>
                    
                    @php
                        $firstTable = $restaurant->tables->first();
                    @endphp
                    @if($firstTable)
                        <a href="{{ route('customer.menu', [$restaurant->id, $firstTable->id]) }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-xl font-bold hover:bg-orange-700 transition shadow-lg shadow-orange-100">
                            <span>Test Your Menu Now</span>
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                        <p class="mt-4 text-[10px] text-gray-400 uppercase font-black tracking-widest">Opening Table {{ $firstTable->table_number }}</p>
                    @else
                        <a href="{{ route('owner.tables.index') }}" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">
                            Create Your First Table
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-8">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <a href="{{ route('owner.profile.edit') }}" class="block p-3 rounded-lg border border-gray-100 hover:bg-orange-50 hover:border-orange-200 transition text-left">
                        <span class="block font-bold text-gray-900">Restaurant Profile</span>
                        <span class="text-xs text-gray-500">Update logo, cover and contact info</span>
                    </a>
                    <a href="{{ route('owner.tables.index') }}" class="block p-3 rounded-lg border border-gray-100 hover:bg-orange-50 hover:border-orange-200 transition text-left">
                        <span class="block font-bold text-gray-900">Tables & QRs</span>
                        <span class="text-xs text-gray-500">Generate and print QR codes</span>
                    </a>
                    <a href="{{ route('owner.categories.index') }}" class="block p-3 rounded-lg border border-gray-100 hover:bg-orange-50 hover:border-orange-200 transition">
                        <span class="block font-bold text-gray-900">Menu Categories</span>
                        <span class="text-xs text-gray-500">Manage your menu sections</span>
                    </a>
                    <a href="{{ route('owner.items.index') }}" class="block p-3 rounded-lg border border-gray-100 hover:bg-orange-50 hover:border-orange-200 transition">
                        <span class="block font-bold text-gray-900">Menu Items</span>
                        <span class="text-xs text-gray-500">Add dishes and update prices</span>
                    </a>
                </div>
            </div>

            <div class="bg-orange-600 rounded-xl p-6 text-white shadow-lg shadow-orange-200">
                <h3 class="text-lg font-black mb-2 uppercase tracking-tighter">Testing Your Menu</h3>
                <p class="text-sm text-orange-100 mb-4">To see orders appear here, go to <strong>Tables & QRs</strong> and click "View Menu" for any table.</p>
                <p class="text-xs text-orange-200">Orders placed via the "Live Demo" on the home page go to a shared demo restaurant, not yours.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function dashboardPolling() {
        return {
            orders: @json($orders),
            stats: @json($stats),
            lastCount: {{ count($orders) }},
            apiError: null,

            init() {
                this.requestNotificationPermission();
                this.lastPoll = new Date().toLocaleTimeString();
                setInterval(() => {
                    this.fetchOrders();
                }, 3000);
            },

            lastPoll: 'Never',

            requestNotificationPermission() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            },

            async fetchOrders() {
                try {
                    const response = await fetch("{{ route('owner.api.orders.live') }}");
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    
                    console.log('Live API response:', { 
                        ordersCount: Array.isArray(data.orders) ? data.orders.length : typeof data.orders,
                        firstOrderId: data.orders && data.orders.length ? data.orders[0].id : 'none',
                        ordersSample: data.orders ? data.orders.slice(0,1) : null,
                        statsTotal: data.stats?.total_orders || 'no stats.total_orders',
                        rawOrders: data.orders,
                        debug: data.debug
                    });
                    
                    if (data.orders && data.orders.length > (this.orders?.length || 0)) {
                        this.notifyNewOrder();
                    }
                    
                    this.orders = Array.isArray(data.orders) ? data.orders : [];
                    this.stats = data.stats || {};
                    this.lastCount = this.orders.length;
                    this.lastPoll = new Date().toLocaleTimeString();
                    this.apiError = null;
                    
                    console.log('Set client orders:', this.orders.length, 'sample:', this.orders[0]);
                } catch (e) {
                    console.error('Failed to fetch orders', e);
                    this.apiError = e.message;
                }
            },

            notifyNewOrder() {
                this.playNotificationSound();
                // Play a subtle sound or show a notification
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('New Order Received!', {
                        body: 'A new order has been placed for your restaurant.'
                    });
                }
            },

            playNotificationSound() {
                try {
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    
                    const playTone = (freq, start, duration) => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);
                        oscillator.type = 'sine';
                        oscillator.frequency.setValueAtTime(freq, audioContext.currentTime + start);
                        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime + start);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + start + duration);
                        oscillator.start(audioContext.currentTime + start);
                        oscillator.stop(audioContext.currentTime + start + duration);
                    };

                    // Pleasant "ding-dong"
                    playTone(660, 0, 0.4);    // E5
                    playTone(523, 0.15, 0.5); // C5
                    
                    console.log('Notification sound played');
                } catch (e) {
                    console.error('Failed to play notification sound', e);
                }
            },

            formatPrice(price) {
                return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
            },

            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
            },

            getStatusClass(status) {
                switch(status) {
                    case 'pending': return 'bg-yellow-100 text-yellow-800';
                    case 'preparing': return 'bg-blue-100 text-blue-800';
                    case 'served': return 'bg-green-100 text-green-800';
                    case 'cancelled': return 'bg-red-100 text-red-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            }
        }
    }
</script>
@endsection
