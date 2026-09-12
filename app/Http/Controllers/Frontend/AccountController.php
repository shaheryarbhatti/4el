<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Customer "My Account" area (frontend).
 *
 * Vendors are sent to their store dashboard instead (see index()), so the same
 * "My Account" link works for everyone based on their role.
 */
class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Approved vendors get their store dashboard, not the customer account.
        if ($user->hasRole('vendor')) {
            return redirect()->route('vendor.dashboard');
        }

        $stats = [
            'orders'    => $user->orders()->count(),
            'pending'   => $user->orders()->where('status', 'pending')->count(),
            'completed' => $user->orders()->where('status', 'completed')->count(),
            'wishlist'  => WishlistService::count(),
        ];

        $recentOrders = $user->orders()->withCount('items')->latest()->take(5)->get();

        return view('frontend.account.dashboard', compact('user', 'stats', 'recentOrders'));
    }

    /** Profile edit form. */
    public function editProfile()
    {
        $user = auth()->user();

        // If no address is saved on the profile yet, pre-fill (display only) from
        // the customer's most recent order's shipping address so their "current"
        // address shows and can be edited/saved.
        if (blank($user->address_line)) {
            $last = $user->orders()->latest()->first();
            if ($last) {
                $user->address_line = $last->address_line;
                $user->city         = $last->city;
                $user->state        = $last->state;
                $user->postal_code  = $last->postal_code;
                $user->country      = $last->country;
                // Not saved — just pre-fills the form (see old('...', $user->...)).
            }
        }

        return view('frontend.account.profile', ['user' => $user]);
    }

    /** Save profile details (name/phone) — password change is optional. */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            // Saved default address
            'address_line' => ['nullable', 'string', 'max:255'],
            'city'         => ['nullable', 'string', 'max:120'],
            'state'        => ['nullable', 'string', 'max:120'],
            'postal_code'  => ['nullable', 'string', 'max:40'],
            'country'      => ['nullable', 'string', 'max:120'],
        ]);

        $user->fill($data)->save();

        // Optional password change.
        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required'],
                'password'         => ['required', 'confirmed', Password::min(8)],
            ]);

            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
            }

            $user->password = $request->password; // hashed by cast
            $user->save();
        }

        return back()->with('success', 'Your account has been updated.');
    }

    /** Dedicated password change (separate form on the profile page). */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.'])
                         ->with('password_tab', true);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('password_success', 'Your password has been changed successfully.');
    }
}
