<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BuyProducts extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $cartItems;
    public array $priceInfo;

    /**
     * Create a new message instance.
     */
    public function __construct($cartItems, $priceInfo)
    {
        $this->cartItems = $cartItems;
        $this->priceInfo = $priceInfo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('brahimelmorabet@gmail.com', 'Team aesthetics'),
            subject: 'Buy Products',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'BuyProductsView',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
