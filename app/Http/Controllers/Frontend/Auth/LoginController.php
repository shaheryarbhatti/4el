<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Customer / vendor login for the storefront (Porto design).
 */
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Blocked accounts cannot sign in.
            if (Auth::user()->status === 'blocked') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been blocked. Please contact support.'])
                    ->withInput($request->only('email'));
            }

            $request->session()->regenerate();
            // Merge any guest (session) wishlist into the user's account.
            \App\Services\WishlistService::mergeToUser(Auth::user());
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', '_form'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
