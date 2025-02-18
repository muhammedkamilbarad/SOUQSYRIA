<?php

namespace App\Services;

use App\Models\Subscribing;
use App\Models\Package;
use App\Repositories\SubscribingRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class SubscribingService
{
    protected $subsribingRepository;

    public function __construct(SubscribingRepository $subscribingRepository)
    {
        $this->subscribingRepository = $subscribingRepository;
    }

    public function getAllSubscribings(): Collection
    {
        return $this->subscribingRepository->getAllWithUsersAndPackages();
    }

    public function getSubscribingById(int $id): ?Model
    {
        try
        {
            return $this->subscribingRepository->getByIdWithUserAndPackage($id);
        }
        catch (ModelNotFoundException $e)
        {
            return null;
        }
    }

    /*
     * Create a new subscribing:
     *  - purchase_date = now
     *  - expiry_date   = now + package->period
     *  - remaining_ads = package->max_ads
     */
    public function createSubscribing(array $data): Model
    {
        // Validate that package_id is present in $data
        $package = Package::findOrFail($data['package_id']);

        // Prepare data
        $data['purchase_date']  = Carbon::now();
        $data['expiry_date']    = Carbon::now()->addDays($package->period);
        $data['remaining_ads']  = $package->max_of_ads;

        echo ($package->max_ads);
        return $this->subscribingRepository->create($data);
    }

    /*
     * Promote or update the subscribing:
     *  - expiry_date   += package->period
     *  - remaining_ads += package->max_ads
     */
    public function updateSubscribing(Model $subscribing, array $data): Model
    {
        // If your "update" means "promote" logic, then:
        $package = Package::findOrFail($data['package_id']);

        // Extend expiry_date
        $newExpiry = $subscribing->expiry_date->addDays($package->period);
        $newRemainingAds = $subscribing->remaining_ads + $package->max_of_ads;

        $updatedData = [
            'expiry_date'   => $newExpiry,
            'remaining_ads' => $newRemainingAds,
        ];
        return $this->subscribingRepository->update($subscribing, $updatedData);
    }

    public function deleteSubscribing(Model $subscribing)
    {
        $this->subscribingRepository->delete($subscribing);
    }
}
