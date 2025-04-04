<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendOtpEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $otp;
    protected $userName;
    protected $timeAmount;

    // The number of time the job may be attempeted if it failed
    public $tries = 4;

    // The maximum number of seconds the job should be allowed to run
    public $timeout = 30;

    // Calculate the number of seconds to wait before retrying the job
    public function backoff()
    {
        return [10, 20, 30];
    }

    // Create a new job instance.
    public function __construct(string $email, string $otp, string $userName, int $timeAmount)
    {
        $this->email = $email;
        $this->otp = $otp;
        $this->userName = $userName;
        $this->timeAmount = $timeAmount;

         // set the queue explicitly
        $this->onQueue('otp');
    }

    // Execute the job.
    public function handle(): void
    {
        // If the email contains '1', simulate an error:
        // if (strpos($this->email, '1') !== false) {
        //     throw new \Exception('Simulated error: The email contains the digit "1".');
        // }
        Mail::to($this->email)->send(new OtpMail($this->otp, $this->userName, $this->timeAmount));
    }

    // Handle a job failure
    public function failed(Throwable $exception): void
    {
        Log::error('OTP email sending failed: ' . $exception->getMessage(), [
            'email' => $this->email,
            'exception' => $exception,
        ]);
    }
}
