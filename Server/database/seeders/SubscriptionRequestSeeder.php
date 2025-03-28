<?php

namespace Database\Seeders;

use App\Models\SubscriptionRequest;
use App\Models\User;
use App\Models\Package;
use App\Services\SubscribingService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SubscriptionRequestSeeder extends Seeder
{
    protected $subscribingService;

    public function __construct(SubscribingService $subscribingService)
    {
        $this->subscribingService = $subscribingService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure we have test users and packages
        if (User::count() == 0) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        if (Package::count() == 0) {
            $this->command->warn('No packages found. Please run PackageSeeder first.');
            return;
        }

        // Create sample receipt files
        if (!Storage::disk('public')->exists('receipts')) {
            Storage::disk('public')->makeDirectory('receipts');
        }

        $users = User::all();
        $packages = Package::all();
        $statuses = ['pending', 'approved', 'rejected'];

        // Create 20 sample subscription requests
        foreach (range(1, 20) as $index) {
            $user = $users->random();
            $package = $packages->random();
            $status = $statuses[array_rand($statuses)];
            $createdAt = Carbon::now()->subDays(rand(1, 30));
            
            $data = [
                'user_id' => $user->id,
                'package_id' => $package->id,
                'status' => $status,
                'receipt' => 'receipts/receipt-' . $index . '.pdf',
                'created_at' => $createdAt,
                'processed_at' => $status !== 'pending' ? $createdAt->copy()->addHours(rand(1, 48)) : null,
                'message' => $this->getStatusMessage($status),
            ];

            // Create dummy receipt file
            Storage::disk('public')->put(
                $data['receipt'],
                'Sample receipt content for subscription request #' . $index
            );

            $request = SubscriptionRequest::create($data);

            // If request is approved, create or update subscribing
            if ($status === 'approved') {
                $activeSubscription = $this->subscribingService->getCurrentActiveSubscription($user->id);
                
                if ($activeSubscription) {
                    $this->subscribingService->updateSubscribing($activeSubscription, [
                        'package_id' => $package->id
                    ]);
                } else {
                    $this->subscribingService->createSubscribing([
                        'user_id' => $user->id,
                        'package_id' => $package->id,
                    ]);
                }
            }
        }

        $this->command->info('Subscription requests and corresponding subscribings seeded successfully!');
    }

    /**
     * Get a sample message based on the status
     *
     * @param string $status
     * @return string|null
     */
    private function getStatusMessage(string $status): ?string
    {
        switch ($status) {
            case 'approved':
                $messages = [
                    'Payment verified successfully',
                    'Subscription request approved',
                    'Welcome to premium membership!',
                ];
                break;
            case 'rejected':
                $messages = [
                    'Invalid payment receipt',
                    'Payment amount does not match package price',
                    'Receipt already used for another subscription',
                ];
                break;
            default:
                return null;
        }

        return $messages[array_rand($messages)];
    }
}