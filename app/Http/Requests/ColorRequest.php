<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:50|unique:colors,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '.اسم اللون مطلوب',
            'name.string' => '.يجب أن يكون اسم اللون نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز اسم اللون 50 حرفًا',
            'name.unique' => '.اسم اللون هذا موجود بالفعل. يرجى اختيار اسم آخر',
        ];
    }
}
