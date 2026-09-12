<?php

namespace App\Services;

use App\Models\Order;

/**
 * PaymentService
 * ------------------------------------------------------------------
 * Talks to Stripe and PayPal via their installed SDK packages.
 *
 * Stripe  → stripe/stripe-php v21 (Stripe Checkout Session, hosted page).
 * PayPal  → srmklive/paypal v3  (Orders v2: create → approve → capture).
 *
 * All credentials come from admin Settings (setting('stripe_secret') etc.)
 * for Stripe, and from config/paypal.php (env-driven) for PayPal.
 *
 * Public method signatures are intentionally stable — CheckoutController
 * calls these and must not need to change when the implementation changes.
 */
class PaymentService
{
    /* ================= COD ================= */

    /** Cash on Delivery is on unless explicitly disabled in settings. */
    public static function codEnabled(): bool
    {
        return setting('cod_enabled', '1') !== '0';
    }

    /* ================= STRIPE ================= */

    public static function stripeEnabled(): bool
    {
        return (bool) setting('stripe_enabled') && (bool) setting('stripe_secret');
    }

    /**
     * Create a Stripe Checkout Session for an order and return its hosted URL.
     * Returns null on any failure (never throws).
     */
    public function stripeCheckoutUrl(Order $order, string $successUrl, string $cancelUrl): ?string
    {
        try {
            \Stripe\Stripe::setApiKey(setting('stripe_secret'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'mode'                 => 'payment',
                'success_url'          => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => $cancelUrl,
                'client_reference_id'  => (string) $order->id,
                'metadata'             => ['order_id' => $order->id],
                'line_items'           => [[
                    'quantity'   => 1,
                    'price_data' => [
                        'currency'     => strtolower($order->currency ?: 'usd'),
                        'unit_amount'  => (int) round($order->grand_total * 100), // cents
                        'product_data' => [
                            'name' => 'Order ' . $order->order_number,
                        ],
                    ],
                ]],
            ]);

            // Persist the Stripe session id so we can verify it on the success URL.
            $order->update(['transaction_id' => $session->id]);

            return $session->url;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    /**
     * Retrieve a Stripe Checkout Session and inspect its payment_status.
     * Returns 'paid' | 'unpaid' | 'unknown' (unknown = could not reach Stripe).
     *
     * Stripe only redirects to the success URL AFTER a completed payment, so
     * 'unknown' is treated optimistically by the controller (see CheckoutController::success).
     */
    public function stripeVerify(string $sessionId): string
    {
        try {
            \Stripe\Stripe::setApiKey(setting('stripe_secret'));

            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            return $session->payment_status === 'paid' ? 'paid' : 'unpaid';
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network error — cannot reach Stripe; treat as unknown.
            report($e);
            return 'unknown';
        } catch (\Throwable $e) {
            report($e);
            return 'unknown';
        }
    }

    /* ================= PAYPAL ================= */

    public static function paypalEnabled(): bool
    {
        // Check config/paypal.php values (env-driven) rather than admin settings.
        $mode = config('paypal.mode', 'sandbox');
        return (bool) config("paypal.{$mode}.client_id")
            && (bool) config("paypal.{$mode}.client_secret");
    }

    /**
     * Build and authenticate a srmklive/paypal v3 provider instance.
     */
    private function paypalProvider(): \Srmklive\PayPal\Services\PayPal
    {
        $provider = new \Srmklive\PayPal\Services\PayPal;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        return $provider;
    }

    /**
     * Create a PayPal order and return the buyer-approval URL.
     * Returns null on any failure (never throws).
     */
    public function paypalApprovalUrl(Order $order, string $returnUrl, string $cancelUrl): ?string
    {
        try {
            $provider = $this->paypalProvider();

            $response = $provider->createOrder([
                'intent'           => 'CAPTURE',
                'purchase_units'   => [[
                    'reference_id' => (string) $order->id,
                    'amount'       => [
                        'currency_code' => $order->currency ?: 'USD',
                        'value'         => number_format($order->grand_total, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url'  => $returnUrl,
                    'cancel_url'  => $cancelUrl,
                    'user_action' => 'PAY_NOW',
                    'brand_name'  => setting('site_name', 'Marketplace'),
                ],
            ]);

            if (! isset($response['id'])) {
                logger()->error('PayPal createOrder: no id in response', ['response' => $response]);
                return null;
            }

            // Persist the PayPal order id for reconciliation.
            $order->update(['transaction_id' => $response['id']]);

            // Find the approval link.
            foreach ($response['links'] ?? [] as $link) {
                if (($link['rel'] ?? '') === 'approve') {
                    return $link['href'];
                }
            }

            logger()->error('PayPal createOrder: no approve link', ['response' => $response]);
            return null;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    /**
     * Capture an approved PayPal order (called on the return URL).
     * Returns true only when PayPal confirms COMPLETED status.
     * Never throws.
     */
    public function paypalCapture(string $paypalOrderId): bool
    {
        try {
            $provider = $this->paypalProvider();

            $response = $provider->capturePaymentOrder($paypalOrderId);

            return ($response['status'] ?? '') === 'COMPLETED';
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}
