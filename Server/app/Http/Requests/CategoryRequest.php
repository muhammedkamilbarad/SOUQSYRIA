<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{

    // Get the validation rules that apply to the request.
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'has_brand' => ['required', 'boolean'],
        ];
    }

    // Custom validation messages
    public function messages(): array
    {
        return [
            'name.required' => '.اسم الفئة مطلوب',
            'name.string' => '.يجب أن يكون اسم الفئة نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز اسم الفئة 100 حرف',
            'name.unique' => '.اسم الفئة هذا مستخدم بالفعل. يرجى اختيار اسم آخر',
            'has_brand.required' => '.حقل "هل يحتوي على علامة تجارية" مطلوب',
            'has_brand.boolean' => '.يجب أن يكون حقل "هل يحتوي على علامة تجارية" صحيحًا أو خطأ',
        ];
    }
}
