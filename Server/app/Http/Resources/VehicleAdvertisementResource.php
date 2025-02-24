<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleAdvertisementResource extends JsonResource
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
            'year' => $this->year,
            'engine_capacity' => $this->engine_capacity,
            'horsepower' => $this->horsepower,
            'condition' => $this->condition,
            // Related resources
            'brand' => new VehicleBrandResource($this->whenLoaded('brand')),
            'model' => new VehicleModelResource($this->whenLoaded('model')),
            'color' => new ColorResource($this->whenLoaded('color')),
            'fuel_type' => new FuelTypeResource($this->whenLoaded('fuelType')),
            'transmission' => new TransmissionResource($this->whenLoaded('transmission')),
        ];
    }
}
