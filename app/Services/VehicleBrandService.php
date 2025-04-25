<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\VehicleBrandRepository;

class VehicleBrandService
{
    protected $vehicleBrandRepository;

    public function __construct(VehicleBrandRepository $vehicleBrandRepository)
    {
        $this->vehicleBrandRepository = $vehicleBrandRepository;
    }

    public function getAllVehicleBrands(): Collection
    {
        return $this->vehicleBrandRepository->getAll();
    }

    public function getVehicleBrandById(int $id): ?Model
    {
        try
        {
            return $this->vehicleBrandRepository->getById($id);
        }
        catch (ModelNotFoundException $e)
        {
            return null;
        }
    }

    public function createVehicleBrand(array $data): Model
    {
        return $this->vehicleBrandRepository->create($data);
    }

    public function updateVehicleBrand(Model $vehicleBrand, array $data): Model
    {
        return $this->vehicleBrandRepository->update($vehicleBrand, $data);
    }

    public function deleteVehicleBrand(Model $vehicleBrand)
    {
        $this->vehicleBrandRepository->delete($vehicleBrand);
    }

    public function getBrandsByCategory(array $data)
    {
        $category_id = $data['category_id'] ?? null;

        return $this->vehicleBrandRepository->getBrandsByCategory($category_id);
    }
}
