<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

/**
 * Vendor dashboard home (frontend, Porto-styled).
 * Shows quick stats for the logged-in vendor's own store.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // All stats are scoped to THIS vendor's products only.
        $stats = [
            'total'    => $user->products()->count(),
            'approved' => $user->products()->where('status', 'approved')->count(),
            'pending'  => $user->products()->where('status', 'pending')->count(),
            'out'      => $user->products()->where('stock', 0)->count(),
        ];

        $recent = $user->products()->latest()->take(5)->get();

        return view('vendor.dashboard', compact('stats', 'recent'));
    }
}
