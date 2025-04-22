<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandVehicleAttributesResource extends JsonResource
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
            'mileage' => $this->mileage,
            'engine_capacity' => $this->engine_capacity,
            'cylinders' => $this->cylinders,
            'transmission_type' => $this->transmission_type,
        ];
    }
}
