<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\PackageRepository;

class PackageService
{
    protected $PackageRepository;

    public function __construct(PackageRepository $PackageRepository)
    {
        $this->PackageRepository = $PackageRepository;
    }

    public function getAllPackages(): Collection
    {
        return $this->PackageRepository->getPackagesWithSubscribersCount();
    }

    public function getPackageById(int $id): ?Model
    {
        try {
            return $this->PackageRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function createPackage(array $data): Model
    {
        return $this->PackageRepository->create($data);
    }

    public function updatePackage(Model $package, array $data): Model
    {
        return $this->PackageRepository->update($package, $data);
    }

    public function changeStatus(int $id)
    {
        return $this->PackageRepository->changeStatus($id);
    }
}
