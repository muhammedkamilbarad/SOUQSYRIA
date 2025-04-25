<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\VehicleModelRepository;


class VehicleModelService
{
    protected $vehicleModelRepository;

    public function __construct(VehicleModelRepository $vehicleModelRepository)
    {
        $this->vehicleModelRepository = $vehicleModelRepository;
    }

    public function getAllVehicleModels(): Collection
    {
        return $this->vehicleModelRepository->getAll();
    }

    public function getVehicleModelById(int $id): ?Model
    {
        try
        {
            return $this->vehicleModelRepository->getById($id);
        }
        catch (ModelNotFoundException $e)
        {
            return null;
        }
    }

    public function createVehicleModel(array $data): Model
    {
        return $this->vehicleModelRepository->create($data);
    }

    public function updateVehicleModel(Model $vehicleModel, array $data): Model
    {
        return $this->vehicleModelRepository->update($vehicleModel, $data);
    }

    public function deleteVehicleModel(Model $vehicleModel)
    {
        $this->vehicleModelRepository->delete($vehicleModel);
    }

    public function getVehicleModelsByBrandId(int $brandId): Collection
    {
        return $this->vehicleModelRepository->getByBrandId($brandId);
    }
}