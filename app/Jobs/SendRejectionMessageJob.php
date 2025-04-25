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

class SendRejectionMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $name;
    protected $message;

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
    public function __construct(string $email, string $name, string $message)
    {
        $this->email = $email;
        $this->name = $name;
        $this->message = (string) $message;

        // Assign to specific queue
        $this->onQueue('default');
    }

    // Execute the job.
    public function handle(): void
    {
        Mail::to($this->email)->send(new RejectionMessage([
            'name' => $this->name,
            'title' => $this->message
        ]));
    }

    // Handle a job failure
    public function failed(Throwable $exception): void
    {
        Log::error('Advertisement rejection email sending failed: ' . $exception->getMessage(), [
            'email' => $this->email,
            'exception' => $exception,
        ]);
    }
}
