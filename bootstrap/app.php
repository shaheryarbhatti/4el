<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // ── FRONTEND routes (customer/vendor facing storefront) ──────────────
        web: __DIR__.'/../routes/web.php',

        // ── ADMIN routes ─────────────────────────────────────────────────────
        // All admin routes live in a SEPARATE file (routes/admin.php).
        // They are automatically:
        //   • prefixed with URL      "/admin"      (e.g. /admin/dashboard)
        //   • prefixed with route    "admin."      (e.g. route('admin.dashboard'))
        //   • protected by the "auth" + "admin" middleware (see the group below)
        // Keeping admin routes here means you never mix them with frontend routes.
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },

        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register short "aliases" so routes can use ->middleware('admin') etc.
        $middleware->alias([
            // Blocks any user that is not an admin from the admin panel.
            'admin'  => \App\Http\Middleware\EnsureUserIsAdmin::class,
            // Gates the frontend vendor area to approved vendors only.
            'vendor' => \App\Http\Middleware\EnsureUserIsVendor::class,
            // Spatie role/permission middleware (used for vendor gating etc.)
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // When an unauthenticated request hits an /admin/* route, redirect to
        // the admin login page instead of the default frontend /login route.
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
        });
    })->create();
