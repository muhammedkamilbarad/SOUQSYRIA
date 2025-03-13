<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class MarineTypeRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:50|unique:marine_types,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'يجب أن يكون الاسم نصًا صالحًا.',
            'name.max' => 'يجب ألا يتجاوز الاسم 50 حرفًا.',
            'name.unique' => '.اسم نوع المركبات البحرية هذا موجود بالفعل. يرجى اختيار اسم آخر',
        ];
    }
}
