<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetBrandByCategoryRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'category_id' =>'nullable|integer|between:3,5',
        ];
    }

    public function messages(): array
    {
        return [
            'number.integer' => 'يجب أن يكون الحقل رقمًا صحيحًا.',
            'number.between' => 'يجب أن يكون الرقم بين 3 و 5.',
        ];
    }
}