<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Package;


class PackageRepository extends BaseRepository
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }

    public function getPackagesWithSubscribersCount()
    {
        return $this->model->withCount([
            'subscribings as active_subscribers_count' => function ($query) {
                $query->where('expiry_date', '>', now())
                    ->where('remaining_ads', '>', 0);
            },
            'subscribings as total_subscribers_count'
        ])->get();
    }

    public function create(array $data): Model
    {
        // Create the package
        $package = $this->model->create($data);
        
        // Reload the model with the subscriber counts
        return $this->model->where('id', $package->id)
            ->withCount([
                'subscribings as active_subscribers_count' => function ($query) {
                    $query->where('expiry_date', '>', now())
                        ->where('remaining_ads', '>', 0);
                },
                'subscribings as total_subscribers_count'
            ])
            ->first();
    }

    public function update(Model $model, array $data): Model
    {
        // Perform the update
        $model->update($data);
        
        // Reload the model with the subscriber counts
        return $this->model->where('id', $model->id)
            ->withCount([
                'subscribings as active_subscribers_count' => function ($query) {
                    $query->where('expiry_date', '>', now())
                        ->where('remaining_ads', '>', 0);
                },
                'subscribings as total_subscribers_count'
            ])
            ->first();
    }

    public function changeStatus(int $packageId): ?Model
    {
        try {
            // First get the package to check its current status
            $package = $this->model->findOrFail($packageId);
            
            // Toggle the status
            $newStatus = !$package->is_active;
            $package->update(['is_active' => $newStatus]);
            
            // Return the updated package with subscriber counts
            return $this->model->where('id', $packageId)
                ->withCount([
                    'subscribings as active_subscribers_count' => function ($query) {
                        $query->where('expiry_date', '>', now())
                            ->where('remaining_ads', '>', 0);
                    },
                    'subscribings as total_subscribers_count'
                ])
                ->first();
                
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function getActivePackages()
    {
        return $this->model->where('is_active', true)
            ->where('id', '!=', 1) // Exclude the free package with ID 1
            ->withCount([
                'subscribings as active_subscribers_count' => function ($query) {
                    $query->where('expiry_date', '>', now())
                        ->where('remaining_ads', '>', 0);
                },
                'subscribings as total_subscribers_count'
            ])
            ->orderBy('id', 'asc')
            ->get();
    }

    // Check if the free package is active
    public function isFreePackageActive(): bool
    {
        // Assuming the free package has ID 1
        $freePackage = $this->model->find(1);
        
        // Return false if the freee package doesn't exist
        if (!$freePackage)
        {
            return false;
        }
        return true;
    }

}
