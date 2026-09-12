<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles admin-panel login / logout using the YNEX login design.
 * Only users with the "admin" role are allowed in.
 */
class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        // Already logged in as admin? Skip straight to the dashboard.
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Try to authenticate.
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Enforce the admin role — a customer must not enter the panel.
            if (! Auth::user()->hasRole('admin')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'These credentials do not have admin access.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
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

        return redirect()->route('admin.login');
    }
}
