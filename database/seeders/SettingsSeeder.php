<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Seeds sensible default values for every settings tab so the admin
 * Settings screens are not empty on first run. All of these are editable
 * from the admin panel afterwards.
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // ── General ───────────────────────────────────────────────
            'general' => [
                'site_name'       => 'eBay Clone',
                'site_tagline'    => 'Buy & Sell Everything',
                'currency'        => 'USD',
                'currency_symbol' => '$',
                'contact_email'   => 'support@ebay.test',
                'contact_phone'   => '+1 234 567 890',
                'address'         => '',
                'logo'            => '',
                'favicon'         => '',
            ],
            // ── Payment (Stripe + PayPal) ─────────────────────────────
            'payment' => [
                'stripe_enabled'        => '0',
                'stripe_key'            => '',
                'stripe_secret'         => '',
                'stripe_webhook_secret' => '',
                'paypal_enabled'        => '0',
                'paypal_mode'           => 'sandbox', // sandbox | live
                'paypal_client_id'      => '',
                'paypal_secret'         => '',
            ],
            // ── Commission & Payouts ──────────────────────────────────
            'commission' => [
                'commission_type'       => 'percentage', // percentage | fixed
                'commission_rate'       => '10',         // 10%
                'commission_fixed'      => '0',
                'commission_min'        => '0',          // no minimum
                'commission_max'        => '0',          // 0 = no cap
                'payout_schedule'       => 'manual',     // manual | weekly | biweekly | monthly
                'payout_min_amount'     => '20',
                'payout_hold_days'      => '7',
                'payout_method'         => 'bank',
                'payout_auto_approve'   => '0',
                'listing_free_count'         => '50',
                'listing_fee'                => '0',
                'auction_fee_rate'           => '0',
                'auction_enabled'            => '1',
                'auction_badge_on_cards'     => '1',
                'auction_countdown_on_cards' => '1',
            ],
            // ── Google Maps ───────────────────────────────────────────
            'map' => [
                'google_maps_api_key' => '',
            ],
            // ── SMTP / Email ──────────────────────────────────────────
            'smtp' => [
                'mail_mailer'       => 'smtp',
                'mail_host'         => 'smtp.mailtrap.io',
                'mail_port'         => '2525',
                'mail_username'     => '',
                'mail_password'     => '',
                'mail_encryption'   => 'tls',
                'mail_from_address' => 'no-reply@ebay.test',
                'mail_from_name'    => 'eBay Clone',
            ],
        ];

        foreach ($defaults as $group => $pairs) {
            foreach ($pairs as $key => $value) {
                // firstOrCreate = don't overwrite values already set by the admin.
                Setting::firstOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'group' => $group]
                );
            }
        }
    }
}
