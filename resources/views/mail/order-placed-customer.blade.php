<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4;padding:30px 0;">
    <tr>
        <td align="center">
            <table width="620" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08);">

                {{-- Header --}}
                <tr>
                    <td style="background:#222222;padding:28px 40px;text-align:center;">
                        <p style="margin:0;font-size:22px;font-weight:700;color:#ffffff;letter-spacing:1px;">
                            {{ config('app.name') }}
                        </p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:36px 40px;">

                        <p style="margin:0 0 6px;font-size:22px;font-weight:700;color:#222;">
                            Order Confirmed!
                        </p>
                        <p style="margin:0 0 24px;font-size:14px;color:#666;">
                            Hi {{ $order->customer_name }}, thank you for your purchase. We've received your order and it's being processed.
                        </p>

                        {{-- Order meta --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f8f8;border-radius:4px;padding:16px 20px;margin-bottom:28px;">
                            <tr>
                                <td style="font-size:13px;color:#888;padding:4px 0;">Order Number</td>
                                <td style="font-size:13px;font-weight:700;color:#222;text-align:right;padding:4px 0;">{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;color:#888;padding:4px 0;">Date</td>
                                <td style="font-size:13px;color:#444;text-align:right;padding:4px 0;">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;color:#888;padding:4px 0;">Payment Method</td>
                                <td style="font-size:13px;color:#444;text-align:right;padding:4px 0;">{{ strtoupper($order->payment_method) }}</td>
                            </tr>
                        </table>

                        {{-- Items table --}}
                        <p style="margin:0 0 12px;font-size:15px;font-weight:700;color:#222;">Items Ordered</p>
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:2px solid #eeeeee;">
                                    <th style="text-align:left;font-size:12px;color:#888;font-weight:600;padding:8px 0;text-transform:uppercase;">Product</th>
                                    <th style="text-align:center;font-size:12px;color:#888;font-weight:600;padding:8px 0;text-transform:uppercase;width:60px;">Qty</th>
                                    <th style="text-align:right;font-size:12px;color:#888;font-weight:600;padding:8px 0;text-transform:uppercase;width:90px;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                <tr style="border-bottom:1px solid #f0f0f0;">
                                    <td style="font-size:14px;color:#333;padding:12px 0;">{{ $item->product_name }}</td>
                                    <td style="font-size:14px;color:#333;text-align:center;padding:12px 0;">{{ $item->quantity }}</td>
                                    <td style="font-size:14px;color:#333;text-align:right;padding:12px 0;">
                                        {{ $order->currency }} {{ number_format($item->price * $item->quantity, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Totals --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;border-top:2px solid #eeeeee;">
                            @if ($order->discount_total > 0)
                            <tr>
                                <td style="font-size:13px;color:#666;padding:6px 0;">Discount</td>
                                <td style="font-size:13px;color:#e74c3c;text-align:right;padding:6px 0;">- {{ $order->currency }} {{ number_format($order->discount_total, 2) }}</td>
                            </tr>
                            @endif
                            @if ($order->shipping_total > 0)
                            <tr>
                                <td style="font-size:13px;color:#666;padding:6px 0;">Shipping</td>
                                <td style="font-size:13px;color:#444;text-align:right;padding:6px 0;">{{ $order->currency }} {{ number_format($order->shipping_total, 2) }}</td>
                            </tr>
                            @endif
                            @if ($order->tax_total > 0)
                            <tr>
                                <td style="font-size:13px;color:#666;padding:6px 0;">Tax</td>
                                <td style="font-size:13px;color:#444;text-align:right;padding:6px 0;">{{ $order->currency }} {{ number_format($order->tax_total, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="font-size:16px;font-weight:700;color:#222;padding:10px 0 0;">Total</td>
                                <td style="font-size:16px;font-weight:700;color:#222;text-align:right;padding:10px 0 0;">{{ $order->currency }} {{ number_format($order->grand_total, 2) }}</td>
                            </tr>
                        </table>

                        {{-- CTA Button --}}
                        <div style="text-align:center;margin-top:32px;">
                            <a href="{{ route('account.orders.show', $order) }}"
                               style="display:inline-block;background:#222222;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;padding:13px 32px;border-radius:4px;letter-spacing:.5px;">
                                View Order
                            </a>
                        </div>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f8f8f8;padding:20px 40px;text-align:center;border-top:1px solid #eeeeee;">
                        <p style="margin:0;font-size:12px;color:#aaa;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </p>
                        <p style="margin:6px 0 0;font-size:12px;color:#aaa;">
                            If you have questions about your order, reply to this email or visit our website.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
