<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureUserIsVendor
 * ------------------------------------------------------------------
 * Protects the frontend vendor area (/vendor/*).
 *
 * Flow:
 *   • not logged in            -> login page
 *   • no vendor application    -> the "become a vendor" apply page
 *   • application not approved -> the "pending review" page
 *   • approved vendor          -> allowed through
 */
class EnsureUserIsVendor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $profile = $user->vendorProfile;

        // Hasn't applied yet.
        if (! $profile) {
            return redirect()->route('vendor.apply')
                ->with('info', 'Apply to open your store first.');
        }

        // Applied but not yet approved (pending / rejected).
        if ($profile->status !== 'approved') {
            return redirect()->route('vendor.pending');
        }

        return $next($request);
    }
}
