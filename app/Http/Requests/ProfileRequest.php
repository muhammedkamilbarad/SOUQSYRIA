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
            
            // Image is optional, must be an image file (e.g., jpeg, png), max size 2MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Phone is optional, must be a string, max length 20 characters, and match the regex pattern
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9 ]{7,20}$/|unique:users,phone,',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'يجب أن يكون الاسم نصًا صالحًا.',
            'name.max' => 'يجب ألا يتجاوز الاسم 100 حرف.',

            'image.image' => 'يجب أن تكون الصورة ملف صورة صالح (مثل jpeg، png، jpg، gif).',
            'image.mimes' => 'يجب أن تكون الصورة من نوع jpeg، png، jpg، أو gif.',
            'image.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميغابايت.',

            'phone.string' => 'يجب أن يكون رقم الهاتف نصًا صالحًا.',
            'phone.max' => 'يجب ألا يتجاوز رقم الهاتف 20 رقماً.',
            'phone.regex' => 'يجب أن يكون رقم الهاتف بتنسيق صالح، مع أو بدون رمز البلد.',
            'phone.unique' => 'رقم الهاتف هذا مستخدم بالفعل.',
        ];
    }
}
