<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AdvertisementService;

class DeactivateExpiredAdvertisements extends Command
{
    // The name and signature of the console command.
    protected $signature = 'advertisements:deactivate-expired';
    // The console command description.
    protected $description = 'Deactivate advertisements that have been active for more than 30 days';
    protected AdvertisementService $advertisementService;

    public function __construct(AdvertisementService $advertisementService)
    {
        parent::__construct();
        $this->advertisementService = $advertisementService;
    }

    // Execute the console command.
    public function handle()
    {
        $result = $this->advertisementService->deactivateExpiredAdvertisements();
        $this->info($result['message']);
        return Command::SUCCESS;
    }
}
