<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * SettingController
 * ------------------------------------------------------------------
 * Powers the admin "Settings" area which has 4 tabs:
 *
 *   general  -> site name, tagline, currency, logo, contact info
 *   payment  -> Stripe + PayPal keys and toggles
 *   map      -> Google Maps API key
 *   smtp     -> mail server credentials (for sending emails)
 *
 * Everything is stored in the "settings" key/value table and read back
 * anywhere with the setting('key') helper.
 *
 * To ADD a new field to any tab:
 *   1. add the field name to the matching array in $this->fields()
 *   2. add the input to the matching Blade view (resources/views/admin/settings/*)
 * That's it — saving & loading are handled generically.
 */
class SettingController extends Controller
{
    /**
     * The whitelist of allowed setting keys per tab/group.
     * Only keys listed here can be saved (prevents junk data).
     */
    private function fields(): array
    {
        return [
            'general' => [
                'site_name', 'site_tagline', 'currency', 'currency_symbol',
                'contact_email', 'contact_phone', 'address',
                'logo', 'favicon', 'admin_logo', 'login_logo',
            ],
            'payment' => [
                // Stripe (mode = test/live sandbox switch)
                'stripe_enabled', 'stripe_mode', 'stripe_key', 'stripe_secret', 'stripe_webhook_secret',
                // PayPal
                'paypal_enabled', 'paypal_mode', 'paypal_client_id', 'paypal_secret',
                // Cash on Delivery (toggle from settings)
                'cod_enabled',
            ],
            'map' => [
                'google_maps_api_key',
            ],
            'commission' => array_merge([
                'commission_type', 'commission_rate', 'commission_fixed',
                'commission_min', 'commission_max',
                'payout_schedule', 'payout_min_amount', 'payout_hold_days',
                'payout_method', 'payout_auto_approve',
                'listing_free_count', 'listing_fee', 'auction_fee_rate',
                'auction_enabled', 'auction_badge_on_cards', 'auction_countdown_on_cards',
            ], array_map(
                fn($c) => 'commission_cat_'.$c->id,
                \App\Models\Category::where('parent_id', null)->where('is_active', 1)->get()->all()
            )),
            'vendors' => [
                'vendor_approval_required', 'vendor_welcome_message',
            ],
            'smtp' => [
                'mail_mailer', 'mail_host', 'mail_port', 'mail_username',
                'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
            ],
        ];
    }

    /**
     * Show a settings tab. Defaults to "general".
     */
    public function index(string $group = 'general')
    {
        // Load the current values for just this tab's fields.
        $keys   = $this->fields()[$group] ?? [];
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = setting($key);
        }

        return view("admin.settings.{$group}", [
            'group'  => $group,
            'values' => $values,
        ]);
    }

    /**
     * Save a settings tab.
     */
    public function update(Request $request, string $group)
    {
        $keys = $this->fields()[$group] ?? [];

        foreach ($keys as $key) {
            // Handle file uploads (logo / favicon) separately.
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                Setting::put($key, $path, $group);
                continue;
            }

            // Checkboxes/toggles send nothing when unchecked, so normalise to "0".
            $booleans = ['stripe_enabled', 'paypal_enabled', 'cod_enabled', 'vendor_approval_required', 'payout_auto_approve',
                         'auction_enabled', 'auction_badge_on_cards', 'auction_countdown_on_cards'];
            if (in_array($key, $booleans)) {
                Setting::put($key, $request->boolean($key) ? '1' : '0', $group);
                continue;
            }

            // Only overwrite when the field is present in the request.
            if ($request->has($key)) {
                Setting::put($key, $request->input($key), $group);
            }
        }

        return redirect()
            ->route('admin.settings.index', $group)
            ->with('success', ucfirst($group).' settings saved successfully.');
    }
}
