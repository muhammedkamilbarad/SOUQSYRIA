<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'location' => $this->location,
            'type' => $this->type,
            'ads_status' => $this->ads_status,
            'active_status' => $this->active_status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            'user' => new UserResource($this->whenLoaded('user')),
            'city' => new CityResource($this->whenLoaded('city')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
        ];

            // 'vehicle_details' => new VehicleAdvertisementResource($this->whenLoaded('vehicleAdvertisement')),
            // 'car_details' => new CarAdvertisementResource($this->whenLoaded('carAdvertisement')),
            // 'motorcycle_details' => new MotorcycleAdvertisementResource($this->whenLoaded('motorcycleAdvertisement')),
            // 'marine_details' => new MarineAdvertisementResource($this->whenLoaded('marineAdvertisement')),
            // 'house_details' => new HouseAdvertisementResource($this->whenLoaded('houseAdvertisement')),
            // 'land_details' => new LandAdvertisementResource($this->whenLoaded('landAdvertisement')),

            if ($this->relationLoaded('vehicleAdvertisement') && $this->vehicleAdvertisement) {
                $data['vehicle_details'] = new VehicleAdvertisementResource($this->vehicleAdvertisement);
            }
            if ($this->relationLoaded('carAdvertisement') && $this->carAdvertisement) {
                $data['car_details'] = new CarAdvertisementResource($this->carAdvertisement);
            }
            if ($this->relationLoaded('motorcycleAdvertisement') && $this->motorcycleAdvertisement) {
                $data['motorcycle_details'] = new MotorcycleAdvertisementResource($this->motorcycleAdvertisement);
            }
            if ($this->relationLoaded('marineAdvertisement') && $this->marineAdvertisement) {
                $data['marine_details'] = new MarineAdvertisementResource($this->marineAdvertisement);
            }
            if ($this->relationLoaded('houseAdvertisement') && $this->houseAdvertisement) {
                $data['house_details'] = new HouseAdvertisementResource($this->houseAdvertisement);
            }
            if ($this->relationLoaded('landAdvertisement') && $this->landAdvertisement) {
                $data['land_details'] = new LandAdvertisementResource($this->landAdvertisement);
            }
        return $data;
    }
}
