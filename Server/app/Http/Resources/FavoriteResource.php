<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    // Transform the resource into an array.
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'advs_id' => $this->advs_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        
        // Include advertisement data if it exists, filtered for null values
        if ($this->advertisement) {
            $advertisementData = $this->advertisement->toArray();
            $data['advertisement'] = $this->filterNullValues($advertisementData);
        }
        
        return $data;
    }
    
    // Recursively filter out null values from an array
    protected function filterNullValues(array $data)
    {
        foreach ($data as $key => $value) {
            // If value is null, remove it
            if ($value === null) {
                unset($data[$key]);
            }
            // If value is an array, recursively filter it
            elseif (is_array($value)) {
                $data[$key] = $this->filterNullValues($value);
                
                // If filtering made the array empty, remove it too
                if (empty($data[$key])) {
                    unset($data[$key]);
                }
            }
        }
        
        return $data;
    }
}