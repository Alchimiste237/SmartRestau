<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SmartRestaurant OS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/" class="text-2xl font-bold text-orange-600">SmartRestau OS</a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <div class="flex items-center space-x-4">
                                @if(Auth::user()->role === 'customer')
                                    <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-orange-600">My Orders</a>
                                @elseif(Auth::user()->role === 'owner')
                                    <a href="{{ route('owner.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-orange-600">Owner Dashboard</a>
                                @elseif(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-orange-600">Admin Panel</a>
                                @endif
                                <span class="text-sm text-gray-500">{{ Auth::user()->name ?? Auth::user()->email }}</span>
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-gray-700 hover:text-orange-600">Logout</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('auth.login') }}" class="text-sm font-medium text-gray-700 hover:text-orange-600">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} SmartRestaurant OS. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>
