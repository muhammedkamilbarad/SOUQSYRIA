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
            'features' => FeatureResource::collection($this->whenLoaded('features')),
        ];

        $advertisementRelations = [
            'vehicleAdvertisement' => VehicleAdvertisementResource::class,
            'carAdvertisement' => CarAdvertisementResource::class,
            'motorcycleAdvertisement' => MotorcycleAdvertisementResource::class,
            'marineAdvertisement' => MarineAdvertisementResource::class,
            'houseAdvertisement' => HouseAdvertisementResource::class,
            'landAdvertisement' => LandAdvertisementResource::class,
        ];

        foreach($advertisementRelations as $relation => $resource)
        {
            if($this->relationLoaded($relation) && $this->{$relation}){
                $data["{$relation}_details"] = new $resource($this->{$relation});
            }
        }

        return $data;
    }
}
