<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotorcycleAdvertisementResource extends JsonResource
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
            'cooling_type' => $this->cooling_type,
            'motorcycle_type' => $this->motorcycle_type,
            //'cylinders' => $this->cylinders,
        ];
    }
}
