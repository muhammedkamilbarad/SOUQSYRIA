<?php

namespace App\Services;

use App\Repositories\FeatureRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FeatureService
{
    protected $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function getAllFeatures(): Collection
    {
        return $this->featureRepository->getAll();
    }

    public function getFeatureById(int $id): ?Model
    {
        try {
            return $this->featureRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function createFeature(array $data): Model
    {
        return $this->featureRepository->create($data);
    }

    public function updateFeature(Model $feature, array $data): Model
    {
        return $this->featureRepository->update($feature, $data);
    }

    public function deleteFeature(Model $feature)
    {
        $this->featureRepository->delete($feature);
    }
}
