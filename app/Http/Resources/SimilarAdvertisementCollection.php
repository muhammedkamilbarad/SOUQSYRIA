<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SimilarAdvertisementCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'count' => $this->count(),
            'advertisements' => $this->collection->map(function ($advertisement) {
                return new AdvertisementDetailResource($advertisement);
            }),
        ];
    }
}

