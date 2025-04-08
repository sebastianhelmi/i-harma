<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on role
            $user = Auth::user();
            return match ($user->role->name) {
                'Project Manager' => redirect()->route('pm.dashboard'),
                'Admin' => redirect()->route('admin.dashboard'),
                'Purchasing' => redirect()->route('purchasing.dashboard'),
                'Kepala Divisi' => redirect()->route('head-of-division.dashboard'),
                'Delivery' => redirect()->route('delivery.dashboard'),
                'Inventory' => redirect()->route('inventory.dashboard'),
                default => redirect()->route('home'),
            };
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
