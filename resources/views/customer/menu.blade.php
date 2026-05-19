<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ $restaurant->name }} - Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased pb-32" x-data="cart()">
    <!-- Social Auth Modal (Simplified One-time Registration) -->
    <div x-show="showAuthModal" class="fixed inset-0 z-[200] flex items-center justify-center px-4" style="display: none;">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm" @click="showAuthModal = false"></div>
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full relative shadow-2xl text-center">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-2">One-time Registration</h2>
            <p class="text-sm text-gray-500 mb-6">Enter your name to place your order and track its status.</p>
            
            <form @submit.prevent="simulateSocialAuth">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 text-left">Your Name</label>
                        <input type="text" x-model="authName" placeholder="e.g. John Doe" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500 transition">
                    </div>
                    <button type="submit" :disabled="submitting" class="w-full bg-orange-600 text-white py-4 rounded-xl font-bold hover:bg-orange-700 transition flex justify-center items-center shadow-lg shadow-orange-100">
                        <span x-show="!submitting">Confirm & Continue</span>
                        <span x-show="submitting" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                    </button>
                    <p class="text-[10px] text-gray-400 uppercase tracking-tighter mt-4">Your info is only used for this order session</p>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Details Modal -->
    <div x-show="showPaymentModal" class="fixed inset-0 z-[200] flex items-center justify-center px-4" style="display: none;">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm" @click="showPaymentModal = false"></div>
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full relative shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-gray-900">Mobile Money</h2>
                <div class="flex space-x-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/9/93/MTN_Logo.svg" class="h-6 w-auto" alt="MTN">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c8/Orange_logo.svg" class="h-6 w-auto" alt="Orange">
                </div>
            </div>
            
            <form @submit.prevent="proceedToPaymentPin">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Select Provider</label>
                        <select x-model="momoProvider" class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500 transition">
                            <option value="mtn">MTN MoMo</option>
                            <option value="orange">Orange Money</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Phone Number</label>
                        <input type="tel" x-model="momoPhone" placeholder="6xx xxx xxx" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500 transition" @input="validatePhoneNumber()">
                        <p x-show="phoneValidationError" class="text-xs text-red-600 font-bold mt-1" x-text="phoneValidationError"></p>
                    </div>
                    
                    <div class="bg-orange-50 p-4 rounded-2xl">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500 font-medium">Total to Pay</span>
                            <span class="text-gray-900 font-black" x-text="total.toLocaleString() + ' FCFA'"></span>
                        </div>
                        <p class="text-[10px] text-orange-600 font-bold uppercase tracking-tighter">You will need to confirm with your PIN</p>
                    </div>

                    <button type="submit" :disabled="submitting || isPhoneInvalid" class="w-full bg-orange-600 text-white py-4 rounded-xl font-bold hover:bg-orange-700 transition flex justify-center items-center shadow-lg shadow-orange-100 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!submitting">Continue to PIN</span>
                        <span x-show="submitting" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                    </button>
                    <button type="button" @click="showPaymentModal = false" class="w-full text-gray-400 text-xs font-bold py-2 hover:text-gray-600 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment PIN Confirmation Modal -->
    <div x-show="showPinModal" class="fixed inset-0 z-[200] flex items-center justify-center px-4" style="display: none;">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm" @click="showPinModal = false"></div>
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full relative shadow-2xl">
            <h2 class="text-2xl font-black text-gray-900 mb-2 text-center">Confirm PIN</h2>
            <p class="text-sm text-gray-500 mb-6 text-center" x-text="'Enter your ' + momoProvider.toUpperCase() + ' PIN'"></p>
            
            <form @submit.prevent="confirmPaymentPin">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">PIN</label>
                        <input type="password" x-model="momoPin" placeholder="••••" maxlength="4" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm text-center tracking-[1em] focus:ring-2 focus:ring-orange-500 transition" inputmode="numeric">
                        <p x-show="pinValidationError" class="text-xs text-red-600 font-bold mt-1 text-center" x-text="pinValidationError"></p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                        <p class="text-[10px] text-blue-700 font-medium">💡 SIMULATION: Any 4-digit PIN will work.</p>
                    </div>
                    <button type="submit" :disabled="submitting || momoPin.length !== 4" class="w-full bg-green-600 text-white py-4 rounded-xl font-bold hover:bg-green-700 transition flex justify-center items-center shadow-lg shadow-green-100 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!submitting">Pay & Place Order</span>
                        <span x-show="submitting" class="flex items-center space-x-2">
                            <span class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                            <span>Processing...</span>
                        </span>
                    </button>
                    <button type="button" @click="showPinModal = false" class="w-full text-gray-400 text-xs font-bold py-2 hover:text-gray-600 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-4 flex justify-between items-center border-b border-gray-50">
            <div>
                <h1 class="text-xl font-black text-gray-900 leading-none">{{ $restaurant->name }}</h1>
                <p class="text-[10px] text-orange-600 font-black uppercase tracking-widest mt-1">Table {{ $table->table_number }}</p>
            </div>
            <button @click="showCart = true" class="relative p-2.5 bg-orange-600 rounded-2xl text-white shadow-lg shadow-orange-100 transition active:scale-90">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span x-show="count > 0" x-text="count" class="absolute -top-1 -right-1 bg-white text-orange-600 text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-orange-600"></span>
            </button>
        </div>
        
        <!-- Category Navigation -->
        <div class="bg-white overflow-x-auto no-scrollbar py-3 px-4 flex space-x-2 border-b border-gray-100">
            <template x-for="cat in {{ json_encode($restaurant->menuCategories->pluck('name')) }}">
                <button @click="scrollToCategory(cat)" class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-bold border border-gray-100 text-gray-500 hover:bg-orange-50 hover:text-orange-600 transition" x-text="cat"></button>
            </template>
        </div>
    </header>

    <!-- Search Bar -->
    <div class="max-w-md mx-auto px-4 mt-6">
        <div class="relative">
            <input type="text" x-model="search" placeholder="Search for dishes..." class="w-full bg-white border-none rounded-2xl py-4 px-12 text-sm shadow-sm focus:ring-2 focus:ring-orange-500 transition">
            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- Menu Sections -->
    <main class="max-w-md mx-auto px-4 mt-8 space-y-12">
        @foreach($restaurant->menuCategories as $category)
            @if($category->items->count() > 0)
                <section id="cat-{{ Str::slug($category->name) }}" x-show="categoryHasVisibleItems({{ json_encode($category->items->pluck('name')) }})">
                    <div class="flex items-center space-x-3 mb-6">
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">{{ $category->name }}</h2>
                        <div class="h-1 flex-grow bg-orange-100 rounded-full"></div>
                    </div>
                    <div class="space-y-6">
                        @foreach($category->items as $item)
                            <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-50 flex gap-5 transform transition active:scale-[0.98]" 
                                 x-show="'{{ strtolower($item->name) }}'.includes(search.toLowerCase())">
                                @if($item->photo_path)
                                    <img src="{{ Storage::url($item->photo_path) }}" alt="{{ $item->name }}" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300 flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-grow flex flex-col justify-between py-0.5">
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $item->name }}</h3>
                                        <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ $item->description }}</p>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-orange-600 font-bold text-sm">{{ number_format($item->price, 0, ',', ' ') }} FCFA</span>
                                        <button @click="addItem({{ json_encode($item) }})" class="bg-orange-600 text-white p-1 rounded-lg hover:bg-orange-700 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach
    </main>

    <!-- Cart Sidebar/Modal -->
    <div x-show="showCart" class="fixed inset-0 z-[100] overflow-hidden" style="display: none;">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCart = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex">
            <div class="w-screen max-w-md bg-white shadow-xl flex flex-col">
                <div class="px-4 py-6 bg-orange-600 text-white flex justify-between items-center">
                    <h2 class="text-lg font-bold">Your Order</h2>
                    <button @click="showCart = false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-grow overflow-y-auto px-4 py-6">
                    <template x-if="items.length === 0">
                        <div class="text-center py-20 text-gray-500">Your cart is empty</div>
                    </template>
                    <div class="space-y-6">
                        <template x-for="(item, index) in items" :key="item.id">
                            <div class="flex justify-between items-start">
                                <div class="flex-grow">
                                    <h4 x-text="item.name" class="font-bold"></h4>
                                    <p x-text="item.price.toLocaleString() + ' FCFA'" class="text-xs text-gray-500"></p>
                                    <input type="text" x-model="item.notes" placeholder="Notes (optional)" class="mt-1 w-full text-xs border-b border-gray-200 focus:outline-none focus:border-orange-500 py-1">
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <button @click="updateQty(index, -1)" class="p-1 bg-gray-100 rounded">-</button>
                                    <span x-text="item.quantity" class="text-sm font-bold w-4 text-center"></span>
                                    <button @click="updateQty(index, 1)" class="p-1 bg-gray-100 rounded">+</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="border-t border-gray-200 p-6 bg-gray-50 space-y-4">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span x-text="total.toLocaleString() + ' FCFA'"></span>
                    </div>

                    <div x-show="items.length > 0">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="paymentMethod = 'momo'" :class="paymentMethod === 'momo' ? 'border-orange-600 bg-orange-50' : 'border-gray-200'" class="border p-2 rounded-lg text-xs font-bold flex flex-col items-center">
                                <span>Mobile Money</span>
                            </button>
                            <button @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'border-orange-600 bg-orange-50' : 'border-gray-200'" class="border p-2 rounded-lg text-xs font-bold flex flex-col items-center">
                                <span>Pay at Counter</span>
                            </button>
                        </div>
                    </div>

                    <button @click="submitOrder()" :disabled="submitting || items.length === 0" class="w-full bg-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-orange-700 disabled:opacity-50">
                        <span x-show="!submitting">Confirm & Pay</span>
                        <span x-show="submitting">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Cart Bar -->
    <div x-show="count > 0 && !showCart" class="fixed bottom-6 left-4 right-4 z-40" style="display: none;">
        <button @click="showCart = true" class="w-full bg-orange-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex justify-between items-center transform transition active:scale-95">
            <div class="flex items-center space-x-3">
                <span class="bg-white text-orange-600 text-xs font-black h-6 w-6 rounded-full flex items-center justify-center" x-text="count"></span>
                <span class="font-bold">View Order</span>
            </div>
            <span class="font-black" x-text="total.toLocaleString() + ' FCFA'"></span>
        </button>
    </div>

    <script>
        function cart() {
            return {
                items: [],
                showCart: false,
                submitting: false,
                showAuthModal: false,
                showPaymentModal: false,
                showPinModal: false,
                authName: '',
                authEmail: '',
                momoProvider: 'mtn',
                momoPhone: '',
                momoPin: '',
                paymentMethod: 'momo',
                search: '',
                isGuest: {{ Auth::check() ? 'false' : 'true' }},
                phoneValidationError: '',
                pinValidationError: '',
                
                init() {
                    // One-time registration: Check if guest name is in local storage or session storage
                    if (this.isGuest) {
                        const savedName = localStorage.getItem('guest_name') || sessionStorage.getItem('guest_name');
                        if (savedName) {
                            this.authName = savedName;
                            // Silently register in background if we have the name
                            this.simulateSocialAuth(true);
                        }
                    }
                },
                
                get isPhoneInvalid() {
                    return this.momoPhone.length < 9 || this.phoneValidationError !== '';
                },

                validatePhoneNumber() {
                    if (this.momoPhone.length === 0) {
                        this.phoneValidationError = '';
                    } else if (this.momoPhone.length < 9) {
                        this.phoneValidationError = 'Phone number must be at least 9 digits';
                    } else if (!/^\d+$/.test(this.momoPhone)) {
                        this.phoneValidationError = 'Phone number must contain only digits';
                    } else {
                        this.phoneValidationError = '';
                    }
                },

                get count() {
                    return this.items.reduce((acc, item) => acc + item.quantity, 0);
                },
                get total() {
                    return this.items.reduce((acc, item) => acc + (item.price * item.quantity), 0);
                },
                addItem(item) {
                    const existing = this.items.find(i => i.id === item.id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.items.push({
                            id: item.id,
                            name: item.name,
                            price: item.price,
                            quantity: 1,
                            notes: ''
                        });
                    }
                },
                updateQty(index, diff) {
                    this.items[index].quantity += diff;
                    if (this.items[index].quantity <= 0) {
                        this.items.splice(index, 1);
                    }
                },
                scrollToCategory(name) {
                    const id = 'cat-' + name.toLowerCase().replace(/ /g, '-');
                    const el = document.getElementById(id);
                    if (el) {
                        const yOffset = -150; 
                        const y = el.getBoundingClientRect().top + window.pageYOffset + yOffset;
                        window.scrollTo({top: y, behavior: 'smooth'});
                    }
                },
                categoryHasVisibleItems(itemNames) {
                    if (this.search === '') return true;
                    return itemNames.some(name => name.toLowerCase().includes(this.search.toLowerCase()));
                },
                async submitOrder() {
                    if (this.isGuest) {
                        this.showAuthModal = true;
                        return;
                    }
                    
                    if (this.paymentMethod === 'momo') {
                        this.showPaymentModal = true;
                        return;
                    }

                    await this.actuallySubmitOrder();
                },
                async simulateSocialAuth(silent = false) {
                    if (!this.authName) {
                        if (!silent) alert('Please enter your name');
                        return;
                    }
                    if (!silent) this.submitting = true;
                    try {
                        const response = await fetch("{{ route('auth.customer.social') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                name: this.authName
                            })
                        });
                        const result = await response.json();
                        if (result.success) {
                            this.isGuest = false;
                            this.showAuthModal = false;
                            // Save name for one-time registration (both localStorage and sessionStorage)
                            localStorage.setItem('guest_name', this.authName);
                            sessionStorage.setItem('guest_name', this.authName);
                            
                            if (!silent) {
                                // After auth, check if we need to show payment modal
                                if (this.paymentMethod === 'momo') {
                                    this.showPaymentModal = true;
                                } else {
                                    await this.actuallySubmitOrder();
                                }
                            }
                        } else {
                            const errorMsg = result.message || 'Registration failed. Please try again.';
                            if (!silent) alert(errorMsg);
                        }
                    } catch (e) {
                        if (!silent) alert('Registration failed. Please try again.');
                        console.error('Auth error:', e);
                    } finally {
                        if (!silent) this.submitting = false;
                    }
                },
                proceedToPaymentPin() {
                    // Validate phone number
                    this.validatePhoneNumber();
                    
                    if (this.isPhoneInvalid) {
                        return;
                    }
                    
                    // Move to PIN modal
                    this.showPaymentModal = false;
                    this.showPinModal = true;
                },
                async confirmPaymentPin() {
                    // Validate PIN
                    if (this.momoPin.length !== 4 || isNaN(this.momoPin)) {
                        this.pinValidationError = 'PIN must be a 4-digit number';
                        return;
                    }

                    this.submitting = true;
                    this.pinValidationError = '';
                    
                    try {
                        // Simulate payment processing with a delay
                        await new Promise(resolve => setTimeout(resolve, 2000));

                        // Call the payment simulation endpoint
                        const response = await fetch("{{ route('customer.payment.simulate', [$restaurant->id, $table->id]) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                items: this.items,
                                payment_method: this.paymentMethod,
                                payment_details: {
                                    provider: this.momoProvider,
                                    phone: this.momoPhone
                                }
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok && result.success) {
                            this.momoPin = '';
                            this.showPinModal = false;
                            window.location.href = result.redirect;
                        } else {
                            const errorMsg = result.message || 'Payment failed. Please try again.';
                            this.pinValidationError = errorMsg;
                            console.error('Payment error:', result);
                        }
                    } catch (e) {
                        console.error('Payment error:', e);
                        this.pinValidationError = 'Connection error. Please check your internet and try again.';
                    } finally {
                        this.submitting = false;
                    }
                },
                async actuallySubmitOrder() {
                    this.submitting = true;
                    try {
                        const response = await fetch("{{ route('customer.order.process', [$restaurant->id, $table->id]) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                items: this.items,
                                payment_method: this.paymentMethod,
                                payment_details: {
                                    provider: this.momoProvider,
                                    phone: this.momoPhone
                                }
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok && result.success) {
                            this.showCart = false;
                            this.items = [];
                            window.location.href = result.redirect;
                        } else {
                            const errorMsg = result.message || 'Order failed. Please check your cart.';
                            alert(errorMsg);
                            console.error('Order error:', result);
                        }
                    } catch (e) {
                        console.error('Submission error:', e);
                        alert('Something went wrong. Please check your connection or try again later.');
                    } finally {
                        this.submitting = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
