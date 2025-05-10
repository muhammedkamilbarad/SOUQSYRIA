<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionCollection extends ResourceCollection
{
    // Transform the resource collection into an array.
    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),
            'total_page' => $this->lastPage(),
            'next_page' => $this->hasMorePages() ? $this->currentPage() + 1 : null,
            'subscriptions' => $this->collection,
        ];
    }
}