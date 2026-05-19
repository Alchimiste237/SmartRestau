<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\OnboardingController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Customer\MenuController;
use Illuminate\Support\Facades\Route;

// Public Home
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/auth/customer/social', [AuthController::class, 'customerSocialAuth'])->name('auth.customer.social');

Route::middleware('auth')->group(function () {
    // Customer Social Auth Simulation
    

    // Customer Routes
    Route::get('/customer/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('customer.dashboard');

    // Owner Routes
    Route::prefix('owner')->name('owner.')->group(function () {
        Route::get('/onboarding', [OnboardingController::class, 'showOnboarding'])->name('onboarding');
        Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/api/orders/live', [DashboardController::class, 'getLiveOrders'])->name('api.orders.live');
        Route::post('/orders/{order}/status', [DashboardController::class, 'updateOrderStatus'])->name('orders.update-status');

        // Restaurant Management
        Route::get('/profile', [\App\Http\Controllers\Owner\RestaurantController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [\App\Http\Controllers\Owner\RestaurantController::class, 'update'])->name('profile.update');

        // Table Management
        Route::resource('tables', \App\Http\Controllers\Owner\TableController::class);
        Route::get('tables/print/pdf', [\App\Http\Controllers\Owner\TableController::class, 'printPdf'])->name('tables.print');

        // Menu Management
        Route::resource('categories', \App\Http\Controllers\Owner\MenuCategoryController::class);
        Route::resource('items', \App\Http\Controllers\Owner\MenuItemController::class);
        Route::post('items/{item}/toggle-availability', [\App\Http\Controllers\Owner\MenuItemController::class, 'toggleAvailability'])->name('items.toggle-availability');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/restaurants/{restaurant}/toggle', [\App\Http\Controllers\Admin\DashboardController::class, 'toggleRestaurantStatus'])->name('restaurants.toggle');
    });
});

// Customer Routes (QR Menu)
Route::get('/r/{restaurant}/t/{table}', [MenuController::class, 'show'])->name('customer.menu');
Route::post('/r/{restaurant}/t/{table}/order', [MenuController::class, 'processOrder'])->middleware('auth')->name('customer.order.process');
Route::post('/r/{restaurant}/t/{table}/payment/simulate', [MenuController::class, 'simulatePayment'])->middleware('auth')->name('customer.payment.simulate');
Route::get('/order/{order}/tracking', [MenuController::class, 'trackOrder'])->name('customer.order-tracking');
