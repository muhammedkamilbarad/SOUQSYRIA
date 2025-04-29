<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // Define the application's command schedule.
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('advertisements:deactivate-expired')->daily();
        $schedule->command('advertisements:deactivate-expired')->everyMinute();
    }


    // Register the commands for the application.
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}


// * * * * * cd /opt/lampp/htdocs/SOUQSYRIA/Server && php artisan schedule:run >> /dev/null 2>&1