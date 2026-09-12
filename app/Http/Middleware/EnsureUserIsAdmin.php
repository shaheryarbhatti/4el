<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureUserIsAdmin
 * ------------------------------------------------------------------
 * Gate that protects the ENTIRE admin panel.
 *
 * A visitor may only continue to any /admin/* route if:
 *   1. They are logged in, AND
 *   2. Their account has the "admin" role (see RoleSeeder).
 *
 * If not logged in  -> sent to the admin login page.
 * If logged in but not admin -> 403 "Forbidden".
 *
 * To change who counts as an admin, edit the check below.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Not authenticated at all -> go log in.
        if (! auth()->check()) {
            return redirect()->route('admin.login');
        }

        // Authenticated but missing the "admin" role -> block.
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
