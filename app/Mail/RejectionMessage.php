<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class RejectionMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    // Create a new message instance.
    public function __construct(array $data)
    {
        $this->data = $data;

        $this->onQueue('default');
    }

    // Get the message envelope.
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@syriasouq.com', 'خدمة العملاء في سوريا سوق'),
            subject: 'تحديث حالة الإعلان',
        );
    }

    // Get the message content definition.
    public function content(): Content
    {
        return new Content(
            view: 'emails.rejection-mail',
            with: [
                'name' => (string) $this->data['name'],
                'reason' => (string) $this->data['title']
            ]
        );
    }

    // Get the attachments for the message.
    public function attachments(): array
    {
        return [];
    }
}
