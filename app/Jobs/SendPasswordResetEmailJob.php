<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\RejectionMessage;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Mail\PasswordResetEmail;

class SendPasswordResetEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $name;
    protected $resetLink;
    protected $expires;

    // The number of time the job may be attempeted if it failed
    public $tries = 4;

    // The maximum number of seconds the job should be allowed to run
    public $timeout = 30;

    // Calculate the number of seconds to wait before retrying the job
    public function backoff()
    {
        return [10, 20, 40];
    }

    // Create a new job instance.
    public function __construct(string $email, string $name, string $resetLink, int $expires)
    {
        $this->email = $email;
        $this->name = $name;
        $this->resetLink = $resetLink;
        $this->expires = $expires;

        // Assign to specific queue
        $this->onQueue('otp');
    }

    // Execute the job.
    public function handle(): void
    {
        Mail::to($this->email)->send(new PasswordResetEmail([
            'name' => $this->name,
            'resetLink' => $this->resetLink,
            'expires' => $this->expires,
        ]));
    }

    // Handle a job failure.
    public function failed(Throwable $exception): void
    {
        Log::error('Password reset email sending failed: ' . $exception->getMessage(), [
            'email' => $this->email,
            'exception' => $exception,
        ]);
    }
}
