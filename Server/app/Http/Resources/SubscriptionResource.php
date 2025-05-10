<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    // Transform the resource into an array.
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
                'name' => $this->user->name,
            ],
            'package' => [
                'id' => $this->package->id,
                'name' => $this->package->name,
                'properties' => $this->package->properties,
                'max_of_ads' => $this->package->max_of_ads,
                'period' => $this->package->period,
                'is_active' => $this->package->is_active,
            ],
            'purchase_date' => $this->purchase_date,
            'expiry_date' => $this->expiry_date,
            'remaining_ads' => $this->remaining_ads,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}