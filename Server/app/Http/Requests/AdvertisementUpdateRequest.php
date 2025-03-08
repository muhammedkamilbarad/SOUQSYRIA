<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Advertisement;
use App\Enums\CategoryType;

class AdvertisementUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('advertisement');
        $advertisement = Advertisement::findOrFail($id);
        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'city_id' => 'sometimes|required|exists:cities,id',
            'location' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:rent,sale',
            'images' => 'sometimes|required|array|max:5',
            'images.*' => 'sometimes|required|image|mimes:jpeg,png,jpg|max:2048',
            'features' => 'array',
            'features.*' => 'exists:features,id'
        ];
        $rules['category_id'] = 'prohibited';


        $category = CategoryType::tryFrom((int) $advertisement->category_id);
        //Add category-specific rules
        $specificRules = match($category)
        {
            CategoryType::LAND => $this->getLandRules(),
            CategoryType::HOUSE => $this->getHouseRules(),
            CategoryType::CAR => array_merge($this->getVehicleRules(), $this->getCarRules()),
            CategoryType::MARINE => array_merge($this->getVehicleRules(), $this->getMarineRules()),
            CategoryType::MOTORCYCLE => array_merge($this->getVehicleRules(), $this->getMotorcycleRules()),
            default => []
        };
        return array_merge($rules, $specificRules);
    }
    // Vehicle Rules
    private function getVehicleRules(): array
    {
        return [
            'color_id' => 'sometimes|required|exists:colors,id',
            'mileage' => 'sometimes|required|numeric|min:0',
            'year' => 'sometimes|required|integer|min:1990|max:' . date('Y'),
            'engine_capacity' => 'sometimes|required|numeric|min:0',
            'fuel_type_id' => 'sometimes|required|exists:fuel_types,id',
            'horsepower' => 'sometimes|required|integer|min:0',
            'transmission_id' => 'sometimes|required|exists:transmission_types,id',
            'condition' => 'sometimes|required|in:NEW,USED',
            'brand_id' => 'prohibited',
            'model_id' => 'prohibited',
        ];
    }
    // House Rules
    private function getHouseRules():array
    {
        return [
            'number_of_rooms' => 'sometimes|required|integer|min:1',
            'building_age' => 'sometimes|required|integer|min:0',
            'square_meters' => 'sometimes|required|numeric|min:0',
            'floor' => 'sometimes|required|integer|min:0'
        ];
    }
    // Car Rules
    private function getCarRules():array
    {
        return [
            'seats' => 'sometimes|required|integer|min:2|max:9',
            'doors' => 'sometimes|required|integer|min:2|max:5'
        ];
    }
    // Marine Rules
    private function getMarineRules():array
    {
        return [
            'marine_type_id' => 'sometimes|required|exists:marine_types,id',
            'length' => 'sometimes|required|numeric|min:0',
            'max_capacity' => 'sometimes|required|integer|min:1'
        ];
    }
    // Land Rules
    private function getLandRules():array
    {
        return [
            'square_meters' => 'sometimes|required|numeric|min:0',
        ];
    }
    // Motorcycle Rules
    private function getMotorcycleRules():array
    {
        return [
            'cylinders' => 'sometimes|required|integer|min:1'
        ];
    }
}
