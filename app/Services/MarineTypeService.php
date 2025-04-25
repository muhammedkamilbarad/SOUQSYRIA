<?php

namespace App\Services;

use App\Repositories\MarineTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MarineTypeService
{
    protected $marineTypeRepository;

    public function __construct(MarineTypeRepository $marineTypeRepository)
    {
        $this->marineTypeRepository = $marineTypeRepository;
    }

    public function getAllMarineTypes(): Collection
    {
        return $this->marineTypeRepository->getAll();
    }

    public function getMarineTypeById(int $id): ?Model
    {
        try {
            return $this->marineTypeRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function createMarineType(array $data): Model
    {
        return $this->marineTypeRepository->create($data);
    }

    public function updateMarineType(Model $marineType, array $data): Model
    {
        return $this->marineTypeRepository->update($marineType, $data);
    }

    public function deleteMarineType(Model $marineType)
    {
        $this->marineTypeRepository->delete($marineType);
    }
}