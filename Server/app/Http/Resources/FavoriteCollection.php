<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FavoriteCollection extends ResourceCollection
{
    // Transform the resource collection into an array.
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($favorite) {
            return [
                'id' => $favorite->id,
                'user_id' => $favorite->user_id,
                'advs_id' => $favorite->advs_id,
                'created_at' => $favorite->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $favorite->updated_at->format('Y-m-d H:i:s'),
                'advertisement' => new AdvertisementDetailResource($favorite->advertisement),
            ];
        })->toArray();
    }
}