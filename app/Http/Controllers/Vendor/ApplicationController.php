<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * "Become a vendor" application (frontend).
 * A logged-in customer submits store details -> a VendorProfile is created
 * with status "pending" -> an admin approves it in the admin panel.
 */
class ApplicationController extends Controller
{
    /**
     * Show the application form (or bounce if they already have a store).
     */
    public function create()
    {
        $profile = auth()->user()->vendorProfile;

        if ($profile && $profile->status === 'approved') {
            return redirect()->route('vendor.dashboard');
        }
        if ($profile) {
            return redirect()->route('vendor.pending');
        }

        return view('vendor.apply');
    }

    /**
     * Store the application.
     */
    public function store(Request $request)
    {
        // Guard against duplicate applications.
        if (auth()->user()->vendorProfile) {
            return redirect()->route('vendor.pending');
        }

        $data = $request->validate([
            'store_name'   => ['required', 'string', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:40'],
            'address'      => ['nullable', 'string', 'max:500'],
            'city'         => ['nullable', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'seller_type'  => ['nullable', 'in:individual,business'],
            // Geo-coordinates captured from Google Places autocomplete.
            'latitude'     => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'    => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        // Combine city into address for storage
        $address = trim(implode(', ', array_filter([
            $data['address'] ?? '',
            $data['city']    ?? '',
        ]))) ?: null;

        // If admin has disabled approval requirement, auto-approve instantly.
        $requiresApproval = setting('vendor_approval_required', '1') === '1';
        $status = $requiresApproval ? 'pending' : 'approved';

        VendorProfile::create([
            'user_id'     => auth()->id(),
            'store_name'  => $data['store_name'],
            'slug'        => Str::slug($data['store_name']).'-'.Str::random(4),
            'phone'       => $data['phone'] ?? null,
            'address'     => $address,
            'latitude'    => $data['latitude'] ?? null,
            'longitude'   => $data['longitude'] ?? null,
            'description' => $data['description'] ?? null,
            'seller_type' => $data['seller_type'] ?? 'individual',
            'status'      => $status,
        ]);

        if (! $requiresApproval) {
            return redirect()->route('vendor.dashboard')
                ->with('success', 'Your store is live! Start adding products.');
        }

        return redirect()->route('vendor.pending')
            ->with('success', 'Your store application has been submitted for review.');
    }

    /**
     * "Application pending / rejected" status page.
     */
    public function pending()
    {
        $profile = auth()->user()->vendorProfile;

        // No application at all -> send them to apply.
        if (! $profile) {
            return redirect()->route('vendor.apply');
        }
        // Already approved -> straight to the dashboard.
        if ($profile->status === 'approved') {
            return redirect()->route('vendor.dashboard');
        }

        return view('vendor.pending', compact('profile'));
    }
}
