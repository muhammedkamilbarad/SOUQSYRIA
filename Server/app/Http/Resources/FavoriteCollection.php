<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FavoriteCollection extends ResourceCollection
{
    // Transform the resource collection into an array.
    public function toArray(Request $request): array
    {
        return [
            'favorites' => $this->collection->map(function ($favorite) {
                return new FavoriteResource($favorite);
            }),
        ];
    }
}