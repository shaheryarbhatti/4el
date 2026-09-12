<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

/**
 * Customer self-registration for the storefront.
 * New sign-ups always get the "customer" role. Becoming a vendor is a
 * separate application flow handled later.
 */
class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'], // auto-hashed by the model cast
        ]);

        // Every new storefront account starts as a customer.
        $user->assignRole('customer');

        Auth::login($user);

        // Carry over any guest (session) wishlist into the new account.
        \App\Services\WishlistService::mergeToUser($user);

        return redirect()->route('home')->with('success', 'Welcome! Your account has been created.');
    }
}
