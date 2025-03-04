<?php

namespace App\Services;

use App\Repositories\FeatureGroupRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FeatureGroupService
{
    protected $featureGroupRepository;

    public function __construct(FeatureGroupRepository $featureGroupRepository)
    {
        $this->featureGroupRepository = $featureGroupRepository;
    }

    public function getAllFeatureGroups(): Collection
    {
        return $this->featureGroupRepository->getAll();
    }

    public function getFeatureGroupById(int $id): ?Model
    {
        try {
            return $this->featureGroupRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function createFeatureGroup(array $data): Model
    {
        return $this->featureGroupRepository->create($data);
    }

    public function updateFeatureGroup(Model $featureGroup, array $data): Model
    {
        return $this->featureGroupRepository->update($featureGroup, $data);
    }

    public function deleteFeatureGroup(Model $featureGroup)
    {
        $this->featureGroupRepository->delete($featureGroup);
    }

    public function getFeaturesWithCategoryId(int $categoryId)
    {
        return $this->featureGroupRepository->getCategoryFeatures($categoryId);
    }
}
