<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules()
    {
        return $this->isMethod('put') || $this->isMethod('patch')
            ? $this->updateRules()
            : $this->storeRules();
    }

    public function storeRules()
    {
        return [];
    }

    private function updateRules()
    {
        $id = $this->route('role') ?? $this->route('id');

        return [
            // Name field is optional (not required)
            'name' => 'nullable|string|max:100',
            
            // Image is optional, it must be a string and max length 255 characters.
            'image' => 'nullable|string|max:255',
            
            // Phone is optional, it must be a string, max length 20 characters, and match the regex pattern.
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9 ]{7,20}$/|unique:users,phone,',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => '.يجب أن يكون الاسم نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز الاسم 100 حرف',

            'image.string' => '.يجب أن يكون رابط الصورة نصًا صالحًا',
            'image.max' => '.يجب ألا يتجاوز حجم الصورة 255 حرفًا',

            'phone.string' => '.يجب أن يكون رقم الهاتف نصًا صالحًا',
            'phone.max' => '.يجب ألا يتجاوز رقم الهاتف 20 رقماً',
            'phone.regex' => '.يجب أن يكون رقم الهاتف بتنسيق صالح، مع أو بدون رمز البلد',
            'phone.unique' => '.رقم الهاتف هذا مستخدم بالفعل',
        ];
    }
}
