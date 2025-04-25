<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarineAdvertisementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->advertisement_id,
            'length' => $this->length,
            'width' => $this->width,
            'engine_brand' => $this->engine_brand,
            'body_material' => $this->body_material,
            'max_capacity' => $this->max_capacity,
            'marine_type' => $this->marine_type,
        ];
    }
}
