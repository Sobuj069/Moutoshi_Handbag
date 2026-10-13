<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('customer.account');
        }
        return view('pages.customer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('customer.account'));
        }

        return back()->withErrors(['email' => 'These credentials do not match our customer records.']);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('customer.account');
        }
        return view('pages.customer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        Auth::login($user);
        return redirect()->route('customer.account')->with('success', 'Welcome to The Patchee BD! Your account has been created.');
    }

    public function account()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('customer.login');
        }

        // Fetch orders by user email or phone
        $orders = Order::where(function($q) use ($user) {
            $q->where('customer_email', $user->email);
            if ($user->phone) {
                $q->orWhere('customer_phone', $user->phone);
            }
        })->with('items')->latest()->get();

        return view('pages.customer-account', compact('user', 'orders'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
