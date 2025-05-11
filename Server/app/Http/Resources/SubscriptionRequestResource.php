<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionRequestResource extends JsonResource
{
    // Transform the resource into an array.
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'status' => $this->status,
            'message' => $this->message,
            'receipt' => $this->receipt,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'processed_at' => $this->processed_at ? $this->processed_at->format('Y-m-d H:i:s') : null,
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
        ];
    }
}