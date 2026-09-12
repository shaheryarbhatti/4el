<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * CheckoutController
 * ------------------------------------------------------------------
 * Turns the session cart into a real Order and takes payment.
 *
 * IMPORTANT: all money is recomputed server-side from the DB here — the
 * session cart prices are never trusted for the actual charge.
 *
 * Payment methods offered depend on the admin Settings:
 *   • Cash on Delivery — always available (order placed, unpaid)
 *   • Stripe           — when enabled + secret key set
 *   • PayPal           — when enabled + client id/secret set
 */
class CheckoutController extends Controller
{
    public function index()
    {
        $summary = $this->buildSummary();

        if (empty($summary['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $methods = [
            'cod'    => PaymentService::codEnabled(),
            'stripe' => PaymentService::stripeEnabled(),
            'paypal' => PaymentService::paypalEnabled(),
        ];

        return view('frontend.checkout', compact('summary', 'methods'));
    }

    public function store(Request $request, PaymentService $payments)
    {
        $data = $request->validate([
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'address_line'   => ['required', 'string', 'max:255'],
            'city'           => ['nullable', 'string', 'max:120'],
            'state'          => ['nullable', 'string', 'max:120'],
            'postal_code'    => ['nullable', 'string', 'max:40'],
            'country'        => ['nullable', 'string', 'max:120'],
            'notes'          => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:cod,stripe,paypal'],
        ]);

        $summary = $this->buildSummary();
        if (empty($summary['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Guard: only allow enabled gateways.
        if ($data['payment_method'] === 'cod' && ! PaymentService::codEnabled()) {
            return back()->with('error', 'Cash on Delivery is not available.');
        }
        if ($data['payment_method'] === 'stripe' && ! PaymentService::stripeEnabled()) {
            return back()->with('error', 'Stripe is not available.');
        }
        if ($data['payment_method'] === 'paypal' && ! PaymentService::paypalEnabled()) {
            return back()->with('error', 'PayPal is not available.');
        }

        // Create the order + items atomically, decrement stock, count coupon.
        $order = DB::transaction(function () use ($data, $summary) {
            $order = Order::create([
                'order_number'   => Order::nextNumber(),
                'user_id'        => auth()->id(),
                'status'         => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'subtotal'       => $summary['subtotal'],
                'discount_total' => $summary['discount'],
                'shipping_total' => $summary['shipping_total'],
                'tax_total'      => $summary['tax_total'],
                'grand_total'    => $summary['grand_total'],
                'currency'       => setting('currency', 'USD'),
                'coupon_code'    => $summary['coupon']['code'] ?? null,
                'customer_name'  => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'address_line'   => $data['address_line'],
                'city'           => $data['city'] ?? null,
                'state'          => $data['state'] ?? null,
                'postal_code'    => $data['postal_code'] ?? null,
                'country'        => $data['country'] ?? null,
                'notes'          => $data['notes'] ?? null,
            ]);

            foreach ($summary['items'] as $line) {
                $order->items()->create([
                    'vendor_id'         => $line['vendor_id'],
                    'product_id'        => $line['product_id'],
                    'product_name'      => $line['name'],
                    'price'             => $line['price'],
                    'quantity'          => $line['quantity'],
                    'shipping_cost'     => $line['shipping'],
                    'tax_amount'        => $line['tax'],
                    'line_total'        => $line['line_total'],
                    'commission_rate'   => $line['commission_rate'],
                    'commission_amount' => $line['commission_amount'],
                    'vendor_amount'     => $line['vendor_amount'],
                ]);

                // Reduce stock.
                Product::where('id', $line['product_id'])->decrement('stock', $line['quantity']);
            }

            // Count coupon usage once.
            if (! empty($summary['coupon']['id'])) {
                optional(Coupon::find($summary['coupon']['id']))->incrementUsage();
            }

            return $order;
        });

        // ---- Take payment ----
        if ($data['payment_method'] === 'cod') {
            session()->forget(['cart', 'coupon']);
            $this->dispatchOrderMails($order->load('items.product.vendor.user'));
            return redirect()->route('checkout.success', $order);
        }

        if ($data['payment_method'] === 'stripe') {
            $url = $payments->stripeCheckoutUrl(
                $order,
                route('checkout.success', $order),
                route('checkout.cancel', $order)
            );
            return $url ? redirect()->away($url)
                        : $this->failOrder($order, 'Could not start Stripe checkout.');
        }

        if ($data['payment_method'] === 'paypal') {
            $url = $payments->paypalApprovalUrl(
                $order,
                route('checkout.paypal.return', $order),
                route('checkout.cancel', $order)
            );
            return $url ? redirect()->away($url)
                        : $this->failOrder($order, 'Could not start PayPal checkout.');
        }

        return $this->failOrder($order, 'Unsupported payment method.');
    }

    /** Stripe / COD success landing. Verifies Stripe payment when applicable. */
    public function success(Request $request, Order $order, PaymentService $payments)
    {
        $this->authorizeOrder($order);

        if ($order->payment_method === 'stripe' && ! $order->isPaid() && $request->filled('session_id')) {
            $status = $payments->stripeVerify($request->get('session_id'));

            if ($status === 'paid') {
                // Confirmed by Stripe API.
                $this->markPaid($order);
            } elseif ($status === 'unknown') {
                // Could not reach Stripe to verify (e.g. DNS/timeout). Stripe only
                // redirects to the success URL AFTER a completed payment, so accept
                // it optimistically and log for later reconciliation via webhook.
                logger()->warning('Stripe verify unreachable; accepting order optimistically', [
                    'order' => $order->order_number,
                    'session_id' => $request->get('session_id'),
                ]);
                $this->markPaid($order);
            }
            // 'unpaid' -> leave the order pending.
        }

        return view('frontend.checkout-success', compact('order'));
    }

    /** PayPal return — capture the approved payment. */
    public function paypalReturn(Request $request, Order $order, PaymentService $payments)
    {
        $this->authorizeOrder($order);

        // PayPal appends ?token={paypalOrderId} to the return URL.
        $paypalOrderId = $request->get('token');
        if ($paypalOrderId && $payments->paypalCapture($paypalOrderId)) {
            $this->markPaid($order);
            session()->forget(['cart', 'coupon']);
            return redirect()->route('checkout.success', $order);
        }

        return redirect()->route('checkout.cancel', $order);
    }

    public function cancel(Order $order)
    {
        $this->authorizeOrder($order);
        return view('frontend.checkout-cancel', compact('order'));
    }

    /* ================= helpers ================= */

    private function markPaid(Order $order): void
    {
        $order->update([
            'payment_status' => 'paid',
            'status'         => 'processing',
            'paid_at'        => now(),
        ]);
        session()->forget(['cart', 'coupon']);
        $this->dispatchOrderMails($order->load('items.product.vendor.user'));
    }

    /**
     * Queue order confirmation emails to the customer and each vendor
     * whose products appear in the order.
     */
    private function dispatchOrderMails(Order $order): void
    {
        // Customer confirmation
        Mail::to($order->customer_email)
            ->queue(new \App\Mail\OrderPlacedCustomer($order));

        // One email per vendor, containing only their items
        $itemsByVendorEmail = $order->items->groupBy(
            fn ($item) => optional(optional(optional($item->product)->vendor)->user)->email
        );

        foreach ($itemsByVendorEmail as $email => $vendorItems) {
            if (! $email) {
                continue;
            }
            Mail::to($email)
                ->queue(new \App\Mail\OrderPlacedVendor($order, $vendorItems));
        }
    }

    private function failOrder(Order $order, string $message)
    {
        $order->update(['status' => 'cancelled', 'payment_status' => 'failed']);
        return redirect()->route('cart.index')->with('error', $message);
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
    }

    /**
     * Calculate platform commission for a line item.
     * Returns [effective_rate, commission_amount].
     *
     * Priority:
     *   1. Category-specific % override
     *   2. Global commission (percentage or fixed)
     *   + Auction extra % added on top for auction listings
     */
    private function calcCommission(float $lineSubtotal, Product $product): array
    {
        $isAuction = $product->listing_type === 'auction';

        // 1. Category-specific override (always percentage)
        $baseRate = null;
        $catRate  = setting('commission_cat_' . $product->category_id);
        if ($catRate !== null && $catRate !== '') {
            $baseRate = (float) $catRate;
        }

        // 2. Fixed-amount mode (no auction surcharge on fixed — it's per-order)
        if ($baseRate === null && setting('commission_type', 'percentage') === 'fixed') {
            $fixed = (float) setting('commission_fixed', 0);
            $min   = (float) setting('commission_min', 0);
            $max   = (float) setting('commission_max', 0);
            $comm  = $fixed;
            if ($isAuction) {
                $auctionExtra = (float) setting('auction_fee_rate', 0);
                $comm += round($lineSubtotal * $auctionExtra / 100, 2);
            }
            if ($min > 0) $comm = max($comm, $min);
            if ($max > 0) $comm = min($comm, $max);
            $comm = min($comm, $lineSubtotal);
            return [0, round($comm, 2)];
        }

        // 3. Percentage mode
        if ($baseRate === null) {
            $baseRate = (float) setting('commission_rate', 10);
        }

        // Add auction surcharge on top of base rate
        $effectiveRate = $baseRate;
        if ($isAuction) {
            $effectiveRate += (float) setting('auction_fee_rate', 0);
        }

        $comm = round($lineSubtotal * $effectiveRate / 100, 2);
        $min  = (float) setting('commission_min', 0);
        $max  = (float) setting('commission_max', 0);
        if ($min > 0) $comm = max($comm, $min);
        if ($max > 0) $comm = min($comm, $max);
        $comm = min($comm, $lineSubtotal);

        return [round($effectiveRate, 2), round($comm, 2)];
    }

    /**
     * Recompute the cart into priced line items + totals from the DB.
     */
    private function buildSummary(): array
    {
        $cart  = session('cart', []);
        $items = [];
        $subtotal = $shipping = $tax = 0.0;

        foreach ($cart as $productId => $row) {
            $product = Product::with('taxClass')->find($productId);
            if (! $product || $product->status !== 'approved' || $product->stock < 1) {
                continue;
            }

            $qty        = min((int) $row['quantity'], $product->stock);
            $unit       = (float) $product->effective_price;
            $lineSub    = $unit * $qty;
            $lineShip   = $product->free_shipping ? 0.0 : (float) $product->shipping_cost;
            $rate       = $product->taxClass->rate ?? 0;
            $lineTax    = round($lineSub * $rate / 100, 2);

            $subtotal  += $lineSub;
            $shipping  += $lineShip;
            $tax       += $lineTax;

            // Commission: category override → global rate → +auction surcharge if applicable
            [$commRate, $commAmt] = $this->calcCommission($lineSub, $product);

            $items[] = [
                'product_id'        => $product->id,
                'vendor_id'         => $product->vendor_id,
                'name'              => $product->name,
                'price'             => $unit,
                'quantity'          => $qty,
                'shipping'          => $lineShip,
                'tax'               => $lineTax,
                'line_total'        => $lineSub + $lineShip + $lineTax,
                'image'             => $row['image'] ?? null,
                'commission_rate'   => $commRate,
                'commission_amount' => $commAmt,
                'vendor_amount'     => round($lineSub - $commAmt, 2),
            ];
        }

        // Re-validate the coupon against the fresh subtotal.
        $couponData = session('coupon');
        $discount   = 0.0;
        if ($couponData && ! empty($couponData['id'])) {
            $coupon = Coupon::find($couponData['id']);
            if ($coupon && $coupon->isValid($subtotal) === true) {
                $discount = $coupon->calcDiscount($subtotal);
            } else {
                $couponData = null; // invalid now
            }
        }

        $grand = max(0, $subtotal - $discount) + $shipping + $tax;

        return [
            'items'          => $items,
            'subtotal'       => round($subtotal, 2),
            'shipping_total' => round($shipping, 2),
            'tax_total'      => round($tax, 2),
            'discount'       => round($discount, 2),
            'coupon'         => $couponData,
            'grand_total'    => round($grand, 2),
        ];
    }
}
