@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">System Admin Dashboard</h1>
        <p class="text-gray-600">Platform overview and management.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 mb-10">
        <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
            <dt class="text-sm font-medium text-gray-500">Total Restaurants</dt>
            <dd class="text-3xl font-black text-orange-600">{{ $stats['total_restaurants'] }}</dd>
        </div>
        <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
            <dt class="text-sm font-medium text-gray-500">Total Users</dt>
            <dd class="text-3xl font-black text-gray-900">{{ $stats['total_users'] }}</dd>
        </div>
        <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
            <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
            <dd class="text-3xl font-black text-gray-900">{{ $stats['total_orders'] }}</dd>
        </div>
        <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
            <dt class="text-sm font-medium text-gray-500">System Revenue</dt>
            <dd class="text-2xl font-black text-green-600">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</dd>
        </div>
    </div>

    <div class="space-y-10">
        <!-- Orders List -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">All Orders</h2>
            </div>
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Restaurant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ substr($order->id, 0, 8) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->restaurant->name ?? 'Deleted' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name ?? 'Guest' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->table ? 'Table ' . $order->table->table_number : 'Unknown' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="max-w-xs">
                                            @foreach($order->items->take(3) as $item)
                                                <div class="text-xs">{{ $item->quantity }}x {{ $item->menuItem->name ?? 'Deleted Item' }}</div>
                                            @endforeach
                                            @if($order->items->count() > 3)
                                                <div class="text-xs italic text-gray-400">+{{ $order->items->count() - 3 }} more</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ number_format($order->total_price, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $order->payment_method ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'preparing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'served') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p class="mt-4 text-lg font-medium text-gray-900">No orders yet</p>
                    <p class="text-sm">They will appear here as soon as customers place orders.</p>
                </div>
            @endif
        </div>

        <!-- Restaurants List -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Registered Restaurants</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Restaurant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($restaurants as $restaurant)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $restaurant->name }}</div>
                                <div class="text-xs text-gray-500">{{ $restaurant->business_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $restaurant->owner->name }}</div>
                                <div class="text-xs text-gray-500">{{ $restaurant->owner->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $restaurant->location }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 {{ $restaurant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-[10px] font-black rounded-full uppercase">
                                    {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.restaurants.toggle', $restaurant) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold {{ $restaurant->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-800' }} uppercase">
                                        {{ $restaurant->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Latest Users -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Latest Users</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $user->role }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
