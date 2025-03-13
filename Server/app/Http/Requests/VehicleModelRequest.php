<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleModelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100',],
            'brand_id' => ['required', 'exists:vehicle_brands,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '.اسم الموديل مطلوب',
            'name.string' => '.يجب أن يكون اسم الموديل نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز اسم الموديل 100 حرف',

            'brand_id.required' => '.معرف العلامة التجارية مطلوب',
            'brand_id.exists' => '.العلامة التجارية المحددة غير صالحة',
        ];
    }
}
