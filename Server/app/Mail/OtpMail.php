<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $userName;
    public $timeAmount;

    // Create a new message instance.
    public function __construct(string $otp, string $userName, int $timeAmount)
    {
        $this->otp = $otp;
        $this->userName = $userName;
        $this->timeAmount = $timeAmount;

        // Assign to specific queue
        $this->onQueue('otp');
    }

    // Get the message envelope.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'رمز التحقق من البريد الإلكتروني',
        );
    }

    // Get the message content definition.
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-mail',
            with: [
                'otp' => $this->otp,
                'userName' => $this->userName,
                'timeAmount' => $this->timeAmount,
            ]
        );
    }

    // Get the attachments for the message.
    public function attachments(): array
    {
        return [];
    }
}
