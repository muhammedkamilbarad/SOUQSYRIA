<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FeatureCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection
            ->groupBy('featureGroup.id')
            ->map(function ($features) {
                return [
                    'id' => $features->first()->featureGroup->id,
                    'name' => $features->first()->featureGroup->name,
                    'category_id' => $features->first()->featureGroup->category_id,
                    'features' => $features->map(function ($feature) {
                        return [
                            'id' => $feature->id,
                            'name' => $feature->name,
                        ];
                    })->values(),
                ];
            })->values();
    }
}
