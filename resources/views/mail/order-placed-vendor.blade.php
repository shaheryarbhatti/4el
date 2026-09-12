<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
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
                            New Order Received!
                        </p>
                        <p style="margin:0 0 24px;font-size:14px;color:#666;">
                            You have a new order. Please prepare the items listed below for dispatch.
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
                                <td style="font-size:13px;color:#888;padding:4px 0;">Customer Name</td>
                                <td style="font-size:13px;color:#444;text-align:right;padding:4px 0;">{{ $order->customer_name }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;color:#888;padding:4px 0;">Ship To</td>
                                <td style="font-size:13px;color:#444;text-align:right;padding:4px 0;">
                                    {{ $order->address_line }}@if($order->city), {{ $order->city }}@endif@if($order->country), {{ $order->country }}@endif
                                </td>
                            </tr>
                        </table>

                        {{-- Vendor items --}}
                        <p style="margin:0 0 12px;font-size:15px;font-weight:700;color:#222;">Your Items in This Order</p>
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:2px solid #eeeeee;">
                                    <th style="text-align:left;font-size:12px;color:#888;font-weight:600;padding:8px 0;text-transform:uppercase;">Product</th>
                                    <th style="text-align:center;font-size:12px;color:#888;font-weight:600;padding:8px 0;text-transform:uppercase;width:60px;">Qty</th>
                                    <th style="text-align:right;font-size:12px;color:#888;font-weight:600;padding:8px 0;text-transform:uppercase;width:100px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $vendorSubtotal = 0; @endphp
                                @foreach ($vendorItems as $item)
                                @php $vendorSubtotal += $item->line_total; @endphp
                                <tr style="border-bottom:1px solid #f0f0f0;">
                                    <td style="font-size:14px;color:#333;padding:12px 0;">{{ $item->product_name }}</td>
                                    <td style="font-size:14px;color:#333;text-align:center;padding:12px 0;">{{ $item->quantity }}</td>
                                    <td style="font-size:14px;color:#333;text-align:right;padding:12px 0;">
                                        {{ $order->currency }} {{ number_format($item->line_total, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Vendor subtotal --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;border-top:2px solid #eeeeee;">
                            <tr>
                                <td style="font-size:16px;font-weight:700;color:#222;padding:10px 0 0;">Your Subtotal</td>
                                <td style="font-size:16px;font-weight:700;color:#222;text-align:right;padding:10px 0 0;">
                                    {{ $order->currency }} {{ number_format($vendorSubtotal, 2) }}
                                </td>
                            </tr>
                        </table>

                        <p style="margin:24px 0 0;font-size:13px;color:#999;">
                            Log in to your vendor dashboard to manage this order and update item statuses.
                        </p>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f8f8f8;padding:20px 40px;text-align:center;border-top:1px solid #eeeeee;">
                        <p style="margin:0;font-size:12px;color:#aaa;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
