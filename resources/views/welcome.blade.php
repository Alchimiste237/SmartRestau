<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartRestaurant OS - Scale Your Restaurant Business</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bg-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#f97316 0.5px, #ffffff 0.5px);
            background-size: 20px 20px;
            background-opacity: 0.1;
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased selection:bg-orange-100 selection:text-orange-600">
    
    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-2xl font-black text-gray-900 tracking-tighter">SmartRestau <span class="text-orange-600">OS</span></span>
            </div>
            <div class="hidden md:flex items-center space-x-8 text-sm font-bold text-gray-600">
                <a href="#features" class="hover:text-orange-600 transition">Features</a>
                <a href="#how-it-works" class="hover:text-orange-600 transition">How it Works</a>
                <a href="#pricing" class="hover:text-orange-600 transition">Pricing</a>
                <a href="{{ route('auth.login') }}" class="px-6 py-2 rounded-full border border-gray-200 hover:border-orange-600 hover:text-orange-600 transition">Login</a>
                <a href="{{ route('auth.register') }}" class="px-6 py-2 rounded-full bg-orange-600 text-white shadow-lg shadow-orange-100 hover:bg-orange-700 transition">Start Free</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-20 overflow-hidden bg-pattern">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-8 relative z-10">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-orange-100 text-orange-600 text-xs font-black uppercase tracking-widest">
                    <span class="flex h-2 w-2 rounded-full bg-orange-600 animate-pulse"></span>
                    <span>The #1 QR Ordering System</span>
                </div>
                <h1 class="text-6xl md:text-7xl font-black text-gray-900 leading-[0.9] tracking-tighter">
                    Double your <span class="text-orange-600">table turnover</span> with ease.
                </h1>
                <p class="text-xl text-gray-500 leading-relaxed max-w-xl">
                    SmartRestau OS is the digital backbone for modern restaurants. Give your customers the power to order from their phones, and give your staff the tools to serve them faster.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('auth.register') }}" class="px-10 py-5 bg-orange-600 text-white rounded-2xl font-black text-xl shadow-2xl shadow-orange-200 hover:bg-orange-700 hover:-translate-y-1 transition text-center">
                        Register Your Restaurant
                    </a>
                    @php
                        $testRestaurant = null;
                        $testTable = null;
                        try {
                            $testRestaurant = \App\Models\Restaurant::where('name', 'Urban Grill')->first();
                            $testTable = $testRestaurant ? $testRestaurant->tables()->where('table_number', '12')->first() : null;
                        } catch (\Exception $e) {
                            // Database might not be ready or tables might not exist yet
                        }
                    @endphp
                    @if($testRestaurant && $testTable)
                        <div class="space-y-4">
                            <a href="{{ route('customer.menu', [$testRestaurant->id, $testTable->id]) }}" target="_blank" class="px-10 py-5 bg-white text-gray-900 border-2 border-gray-100 rounded-2xl font-black text-xl hover:bg-gray-50 transition text-center flex items-center justify-center space-x-2">
                                <span>Live Demo</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            <p class="text-[10px] text-gray-400 text-center uppercase font-bold tracking-widest">Orders from this demo appear in a shared dashboard.</p>
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-6 text-sm text-gray-400 font-bold uppercase tracking-widest">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>No Credit Card</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Instal Setup</span>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -top-20 -left-20 w-80 h-80 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                <div class="relative bg-white p-4 rounded-[2.5rem] shadow-2xl border border-gray-100 transform rotate-2">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80" alt="Dashboard Preview" class="rounded-[2rem]">
                    <div class="absolute -bottom-10 -left-10 bg-white p-6 rounded-3xl shadow-2xl border border-gray-100 max-w-xs transform -rotate-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase">Revenue Boost</p>
                                <p class="text-2xl font-black text-gray-900">+35%</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Average increase in revenue reported by restaurants switching to QR ordering.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem/Solution Section -->
    <section id="features" class="py-32 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter">Everything you need to <span class="text-orange-600">run a smart restaurant.</span></h2>
                <p class="text-lg text-gray-500">Stop making your customers wait. Modernize your service with our all-in-one operating system.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-10 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-8">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">QR Code Ordering</h3>
                    <p class="text-gray-500 leading-relaxed">Unique QR codes for every table. Customers scan, browse, and order without waiting for a waiter.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-10 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition duration-300">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-8">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Live Order Dashboard</h3>
                    <p class="text-gray-500 leading-relaxed">Manage incoming orders in real-time. Status updates (Preparing, Served) are synced instantly with customers.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-10 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition duration-300">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-8">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Real-time Analytics</h3>
                    <p class="text-gray-500 leading-relaxed">Track revenue, popular dishes, and peak hours. Make data-driven decisions to optimize your menu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="how-it-works" class="py-32">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="space-y-12">
                    <h2 class="text-5xl font-black text-gray-900 tracking-tighter">Go live in <span class="text-orange-600">under 10 minutes.</span></h2>
                    
                    <div class="space-y-8">
                        <!-- Step 1 -->
                        <div class="flex items-start space-x-6">
                            <div class="w-12 h-12 rounded-full bg-gray-900 text-white flex items-center justify-center font-black flex-shrink-0">1</div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Register & Setup</h4>
                                <p class="text-gray-500">Create your account and enter your restaurant details, location, and hours.</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-start space-x-6">
                            <div class="w-12 h-12 rounded-full bg-gray-900 text-white flex items-center justify-center font-black flex-shrink-0">2</div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Build Your Menu</h4>
                                <p class="text-gray-500">Add categories, dishes, prices, and photos. Our interface makes menu management a breeze.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start space-x-6">
                            <div class="w-12 h-12 rounded-full bg-gray-900 text-white flex items-center justify-center font-black flex-shrink-0">3</div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Print QR Codes</h4>
                                <p class="text-gray-500">Generate unique QR codes for your tables. Print them on our professional templates.</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('auth.register') }}" class="inline-block px-10 py-5 bg-orange-600 text-white rounded-2xl font-black text-xl shadow-2xl shadow-orange-100 hover:bg-orange-700 transition">
                        Get Started Now
                    </a>
                </div>
                <div class="bg-orange-600 rounded-[3rem] p-12 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                    <blockquote class="text-3xl font-bold italic leading-tight mb-8">
                        "Since implementing SmartRestau OS, our staff spend more time serving and less time taking orders. Our revenue increased by 20% in the first month."
                    </blockquote>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white rounded-full overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80" alt="Owner">
                        </div>
                        <div>
                            <p class="font-black">Amadou Traoré</p>
                            <p class="text-sm opacity-80">Owner, Flavor Hub</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-32 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-5xl font-black mb-6 tracking-tighter">Simple, transparent <span class="text-orange-600">pricing.</span></h2>
            <p class="text-gray-400 text-xl max-w-2xl mx-auto mb-16">Start free and scale as you grow. No hidden fees or long-term contracts.</p>
            
            <div class="max-w-lg mx-auto bg-white text-gray-900 p-12 rounded-[3rem] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-orange-600 text-white px-6 py-2 rounded-bl-3xl font-black text-xs uppercase tracking-widest">Limited Offer</div>
                <p class="text-xl font-bold text-gray-400 uppercase tracking-widest mb-4">Pro Plan</p>
                <div class="flex items-center justify-center space-x-2 mb-8">
                    <span class="text-6xl font-black">25k</span>
                    <span class="text-xl text-gray-400 font-bold">FCFA / mo</span>
                </div>
                <ul class="text-left space-y-4 mb-10">
                    <li class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold">Unlimited Tables & QR Codes</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold">Digital Menu with Photos</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold">Live Order Management Dashboard</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold">Customer Data & History</span>
                    </li>
                </ul>
                <a href="{{ route('auth.register') }}" class="block w-full py-5 bg-orange-600 text-white rounded-2xl font-black text-xl hover:bg-orange-700 transition">
                    Start Your 14-Day Free Trial
                </a>
                <p class="mt-6 text-xs text-gray-400 font-bold uppercase tracking-widest">No credit card required</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-20 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-2 space-y-6">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-black text-gray-900 tracking-tighter">SmartRestau <span class="text-orange-600">OS</span></span>
                </div>
                <p class="text-gray-500 max-w-sm">The digital backbone for the modern hospitality industry. Scale your business, delight your customers.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 hover:bg-orange-600 hover:text-white transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.599 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-black text-gray-900 mb-6 uppercase tracking-widest text-xs">Product</h4>
                <ul class="space-y-4 text-sm font-bold text-gray-500">
                    <li><a href="#features" class="hover:text-orange-600 transition">Features</a></li>
                    <li><a href="#how-it-works" class="hover:text-orange-600 transition">How it Works</a></li>
                    <li><a href="#pricing" class="hover:text-orange-600 transition">Pricing</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-black text-gray-900 mb-6 uppercase tracking-widest text-xs">Legal</h4>
                <ul class="space-y-4 text-sm font-bold text-gray-500">
                    <li><a href="#" class="hover:text-orange-600 transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-orange-600 transition">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-20 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
            <p>&copy; {{ date('Y') }} SmartRestaurant OS. All rights reserved.</p>
            <p>Built with ❤️ for the hospitality industry.</p>
        </div>
    </footer>

</body>
</html>
