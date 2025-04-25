<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdvertisementCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->currentPage(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),
            'total_pages' => $this->lastPage(),
            'next_page' => $this->nextPageUrl() ? $this->currentPage() + 1 : null,
            'advertisements' => $this->collection->map(function ($advertisement) {
                return new AdvertisementDetailResource($advertisement);
            }),
        ];
    }
}
