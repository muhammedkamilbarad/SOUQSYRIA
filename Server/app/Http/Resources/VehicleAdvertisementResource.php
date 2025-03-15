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
            'cylinders' => $this->cylinders,
            'condition' => $this->condition,
            // Related resources
            'brand' => new VehicleBrandResource($this->whenLoaded('vehicleBrand')),
            'model' => new VehicleModelResource($this->whenLoaded('vehicleModel')),
            'color' => $this->color,
            'fuel_type' => $this->fuel_type,
            'transmission_type' => $this->transmission_type,
            //'color' => new ColorResource($this->whenLoaded('color')),
            //'fuel_type' => new FuelTypeResource($this->whenLoaded('fuelType')),
            //'transmission' => new TransmissionResource($this->whenLoaded('transmissionType')),
        ];
    }
}
