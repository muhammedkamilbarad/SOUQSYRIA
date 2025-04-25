<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseAdvertisementResource extends JsonResource
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
            'house_type' => $this->house_type,
            'number_of_rooms' => $this->number_of_rooms,
            'number_of_bathrooms' => $this->number_of_bathrooms,
            'building_age' => $this->building_age,
            'square_meters' => $this->square_meters,
            'floor' => $this->floor,
        ];
    }
}
