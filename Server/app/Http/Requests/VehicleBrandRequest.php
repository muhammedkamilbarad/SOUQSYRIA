<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleBrandRequest extends FormRequest
{
    // Get the validation rules that apply to the request.
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '.اسم العلامة التجارية مطلوب',
            'name.string' => '.يجب أن يكون اسم العلامة التجارية نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز اسم العلامة التجارية 100 حرف',

            'category_id.required' => '.معرف الفئة مطلوب',
            'category_id.exists' => '.الفئة المحددة غير صالحة',
        ];
    }
}
