<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Avoid longer index keys error on older MySQL/MariaDB.
        Schema::defaultStringLength(191);

        // Apply admin-managed settings to the live config so features
        // (email sending, payments, maps) use the values saved in the panel
        // instead of the .env file.
        $this->applyDynamicSettings();
    }

    /**
     * Push settings saved in the admin panel into Laravel's runtime config.
     * Wrapped in try/catch so the app still boots before migrations exist
     * (e.g. during `php artisan migrate`).
     */
    private function applyDynamicSettings(): void
    {
        try {
            // Bail early if the settings table isn't there yet.
            if (! Schema::hasTable('settings')) {
                return;
            }

            // ---- Mail / SMTP ----
            if ($host = setting('mail_host')) {
                config([
                    'mail.default'                 => setting('mail_mailer', 'smtp'),
                    'mail.mailers.smtp.host'       => $host,
                    'mail.mailers.smtp.port'       => setting('mail_port', 587),
                    'mail.mailers.smtp.username'   => setting('mail_username'),
                    'mail.mailers.smtp.password'   => setting('mail_password'),
                    'mail.mailers.smtp.encryption' => setting('mail_encryption') === 'null' ? null : setting('mail_encryption'),
                    'mail.from.address'            => setting('mail_from_address', 'no-reply@ebay.test'),
                    'mail.from.name'               => setting('mail_from_name', setting('site_name', 'eBay Clone')),
                ]);
            }

            // ---- Stripe / PayPal (exposed via config('services.*')) ----
            config([
                'services.stripe.key'    => setting('stripe_key'),
                'services.stripe.secret' => setting('stripe_secret'),
                'services.paypal.mode'      => setting('paypal_mode', 'sandbox'),
                'services.paypal.client_id' => setting('paypal_client_id'),
                'services.paypal.secret'    => setting('paypal_secret'),
            ]);
        } catch (\Throwable $e) {
            // Silent: never break app boot because of settings.
        }
    }
}
