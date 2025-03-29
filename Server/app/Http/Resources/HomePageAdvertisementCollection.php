<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HomePageAdvertisementCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->collection->map(function ($categoryAdvertisements) {
                return collect($categoryAdvertisements)->map(function ($advertisement) {
                    return new AdvertisementDetailResource($advertisement);
                })->toArray();
            }),
        ];
    }
}
