<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionRequestCollection extends ResourceCollection
{
    // The resource that this resource collects.
    public $collects = SubscriptionRequestResource::class;

    // Transform the resource collection into an array.
    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),
            'total_pages' => $this->lastPage(),
            'next_page' => $this->hasMorePages() ? $this->currentPage() + 1 : null,
            'subscription_requests' => $this->collection,
        ];
    }
}