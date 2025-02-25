<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Advertisement;

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
        ];
        switch($advertisement->category_id)
        {
            //Add vehicle-specific rules
            case 3://'car':
            case 5://'motorcycle':
            case 4://'marine':
                $rules = array_merge($rules, [
                    'color_id' => 'sometimes|required|exists:colors,id',
                    'mileage' => 'sometimes|required|numeric|min:0',
                    'year' => 'sometimes|required|integer|min:1990|max:'.date('Y'),
                    'engine_capacity' => 'sometimes|required|numeric|min:0',
                    'brand_id' => 'sometimes|required|exists:vehicle_brands,id',
                    'model_id' => 'sometimes|required|exists:vehicle_models,id',
                    'fuel_type_id' => 'sometimes|required|exists:fuel_types,id',
                    'horsepower' => 'sometimes|required|integer|min:0',
                    'transmission_id' => 'sometimes|required|exists:transmission_types,id',
                    'condition' => 'sometimes|required|in:NEW,USED'
                ]);
                //Add car-specific rules
                if($this->input('category_id') == 3){
                    $rules = array_merge($rules, [
                        'seats' => 'sometimes|required|integer|min:2|max:9',
                        'doors' => 'sometimes|required|integer|min:2|max:5'
                    ]);
                }
                //Add motorcycle-specific rules
                elseif($this->input('category_id') == 5){
                    $rules = array_merge($rules, [
                        'cylinders' => 'sometimes|required|integer|min:1'
                    ]);
                }
                //Add marine-specific rules
                elseif($this->input('category_id') == 4){
                    $rules = array_merge($rules, [
                        'marine_type_id' => 'sometimes|required|exists:marine_types,id',
                        'length' => 'sometimes|required|numeric|min:0',
                        'max_capacity' => 'sometimes|required|integer|min:1'
                    ]);
                }
                break;
            //Add house-specific rules
            case 2://'house':
                $rules = array_merge($rules, [
                    'number_of_rooms' => 'sometimes|required|integer|min:1',
                    'building_age' => 'sometimes|required|integer|min:0',
                    'square_meters' => 'sometimes|required|numeric|min:0',
                    'floor' => 'sometimes|required|integer|min:0'
                ]);
                break;
            //Add land-specific rules
            case 1://'land':
                $rules = array_merge($rules, [
                    'square_meters' => 'sometimes|required|string|max:100'
                ]);
                break;
        }
        return $rules;
    }
}
