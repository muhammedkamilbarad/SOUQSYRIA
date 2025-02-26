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

    public function deactivatePackage(int $packageId)
    {
        try {
            $package = $this->model->where('id', $packageId)->update(['is_active' => false]);
            return $this->model->find($packageId);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

}
