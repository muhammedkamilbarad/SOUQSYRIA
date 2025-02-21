<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'category' => 'required|in:car,motorcycle,marine,house,land'
        ];
        //Add category-specific rules
        switch($this->input('category'))
        {
            //Add vehicle-specific rules
            case 'car':
            case 'motorcycle':
            case 'marine':
                $rules = array_merge($rules, [
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
                ]);
                //Add car-specific rules
                if($this->input('category') === 'car'){
                    $rules = array_merge($rules, [
                        'seats' => 'required|integer|min:2|max:9',
                        'doors' => 'required|integer|min:2|max:5'
                    ]);
                }
                //Add motorcycle-specific rules
                elseif($this->input('category') === 'motorcycle'){
                    $rules = array_merge($rules, [
                        'cylinders' => 'required|integer|min:1'
                    ]);
                }
                //Add marine-specific rules
                elseif($this->input('category') === 'marine'){
                    $rules = array_merge($rules, [
                        'marine_type_id' => 'required|exists:marine_types,id',
                        'length' => 'required|numeric|min:0',
                        'max_capacity' => 'required|integer|min:1'
                    ]);
                }
                break;
            //Add house-specific rules
            case 'house':
                $rules = array_merge($rules, [
                    'number_of_rooms' => 'required|integer|min:1',
                    'building_age' => 'required|integer|min:0',
                    'square_meters' => 'required|numeric|min:0',
                    'floor' => 'required|integer|min:0'
                ]);
                break;
            //Add land-specific rules
            case 'land':
                $rules = array_merge($rules, [
                    'square_meters' => 'required|string|max:100'
                ]);
                break;
        }
        return $rules;
    }
}
