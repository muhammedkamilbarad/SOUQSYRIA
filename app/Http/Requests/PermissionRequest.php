<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:50|unique:permissions,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '.اسم الإذن مطلوب',
            'name.string' => '.يجب أن يكون اسم الإذن نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز اسم الإذن 50 حرفًا',
            'name.unique' => '.اسم الإذن هذا مستخدم بالفعل. يرجى اختيار اسم آخر',
        ];
    }
}
