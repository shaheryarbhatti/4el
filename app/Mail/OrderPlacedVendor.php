<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderPlacedVendor extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public Collection $vendorItems;

    public function __construct(Order $order, Collection $vendorItems)
    {
        $this->order       = $order;
        $this->vendorItems = $vendorItems;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Order Received - ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-placed-vendor',
        );
    }
}
