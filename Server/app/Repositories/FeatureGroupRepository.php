<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\FeatureGroup;
use App\Repositories\BaseRepository;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;


class FeatureGroupRepository extends BaseRepository
{
    public function __construct(FeatureGroup $featureGroup)
    {
        parent::__construct($featureGroup);
    }

    public function create(array $data): Model
    {
        $group = parent::create([
            'name' => $data['name'],
            'category_id' => $data['category_id']
        ]);
        if (!empty($data['features'])) {
            $featureData = [];
            foreach($data['features'] as $featureName){
                $featureData[] = [
                    'name' => $featureName,
                    'feature_group_id' => $group->id,
                ];
            }
            $group->features()->createMany($featureData);
        }
        return $group->load('features');
    }

    public function update(Model $model, array $data): Model
    {
    parent::update($model, [
        'name' => $data['name'] ?? $model->name,
        'category_id' => $data['category_id'] ?? $model->category_id,
    ]);
    if (!empty($data['features']) && is_array($data['features'])) {
        $model->features()->delete();
        $featureData = [];
        foreach ($data['features'] as $featureName) {
            $featureData[] = [
                'name' => $featureName,
                'feature_group_id' => $model->id,
            ];
        }
        $model->features()->createMany($featureData);
    }
    return $model->load('features');
    }

    public function getCategoryFeatures($categoryId)
    {
        $featureGroups = FeatureGroup::with('features')
            ->where('category_id', $categoryId)
            ->get()
            ->map(function ($group) {
                return [
                    'category' => $group->category->name,
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'features' => $group->features->select(['id','name'])
                ];
            });
        return $featureGroups;
    }

    public function getByIdWithFeatures(int $Id)
    {
        return parent::getById($Id)->load('features');
    }
}
