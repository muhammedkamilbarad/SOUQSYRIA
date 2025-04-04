<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    // Create a new message instance.
    public function __construct(array $data)
    {
        $this->data = $data;

        $this->onQueue('otp');
    }

    // Get the message envelope.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Password',
        );
    }

    // Get the message content definition.
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
            with: [
                'name' => (string) $this->data['name'],
                'resetLink' => (string) $this->data['resetLink'],
                'expires' => $this->data['expires'],
            ]
        );
    }

    // Get the attachments for the message.
    public function attachments(): array
    {
        return [];
    }
}
