<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMyAccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '.كلمة المرور الحالية مطلوبة',
        ];
    }
}
