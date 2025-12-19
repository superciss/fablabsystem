<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $productList;

    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->productList = $order->orderItem->pluck('product.name')->toArray();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order is Completed!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_completed',
            with: [
                'userName' => $this->order->user->name,
                'productList' => $this->productList,
                'totalAmount' => $this->order->total_amount,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
