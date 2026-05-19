<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['restaurant', 'items.menuItem'])->orderBy('created_at', 'desc')->get();
        
        return view('customer.dashboard', compact('user', 'orders'));
    }
}
