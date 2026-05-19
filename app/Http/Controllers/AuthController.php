<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'owner') {
                if (!$user->restaurant) {
                    return redirect()->route('owner.onboarding');
                }
                return redirect()->route('owner.dashboard');
            }

            if ($user->role === 'customer') {
                return redirect()->route('customer.dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'owner', // Default registration is for Owners
            'is_verified' => true,
        ]);

        Auth::login($user);

        return redirect()->route('owner.onboarding');
    }

    public function customerSocialAuth(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        $email = $request->email;
        
        if (!$email) {
            // Generate a consistent guest email based on name and browser session
            // This ensures the same guest user is used across the same session
            $slug = Str::slug($request->name);
            $sessionId = session()->getId();
            $email = $slug . '_' . substr($sessionId, 0, 8) . '@guest.smartrestau.os';
        }

        // Simulating social auth - Find or create user without password
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $request->name,
                'role' => 'customer',
                'is_verified' => true,
                'password' => Hash::make(Str::random(16)) 
            ]
        );

        Auth::login($user);

        \Illuminate\Support\Facades\Log::info('Customer social auth', [
            'user_id' => $user->id,
            'email' => $email,
            'name' => $request->name,
        ]);

        return response()->json(['success' => true]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }
}
