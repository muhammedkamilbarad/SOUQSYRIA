<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CategoryType;

class AdvertisementRequest extends FormRequest
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
        //Add common rules for all categories
        $rules =  [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city_id' => 'required|exists:cities,id',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:rent,sale',
            'images' => 'required|array|max:5',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
        $category = CategoryType::tryFrom((int) $this->input('category_id'));
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
    private function getVehicleRules():array
    {
        return [
            'color_id' => 'required|exists:colors,id',
            'mileage' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1990|max:'.date('Y'),
            'engine_capacity' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'horsepower' => 'required|integer|min:0',
            'transmission_id' => 'required|exists:transmission_types,id',
            'condition' => 'required|in:NEW,USED'
        ];
    }
    // House Rules
    private function getHouseRules():array
    {
        return [
            'number_of_rooms' => 'required|integer|min:1',
            'building_age' => 'required|integer|min:0',
            'square_meters' => 'required|numeric|min:0',
            'floor' => 'required|integer|min:0'
        ];
    }
    // Car Rules
    private function getCarRules():array
    {
        return [
            'seats' => 'required|integer|min:2|max:9',
            'doors' => 'required|integer|min:2|max:5'
        ];
    }
    // Marine Rules
    private function getMarineRules():array
    {
        return [
            'marine_type_id' => 'required|exists:marine_types,id',
            'length' => 'required|numeric|min:0',
            'max_capacity' => 'required|integer|min:1'
        ];
    }
    // Land Rules
    private function getLandRules():array
    {
        return [
            'square_meters' => 'required|numeric|min:0',
        ];
    }
    // Motorcycle Rules
    private function getMotorcycleRules():array
    {
        return [
            'cylinders' => 'required|integer|min:1'
        ];
    }
}
