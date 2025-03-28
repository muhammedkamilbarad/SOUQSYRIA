<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login_input' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Check if input matches email or phone format
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    $isPhone = preg_match('/^\+?[0-9]{7,20}$/', $value);
                    
                    if (!$isEmail && !$isPhone) {
                        $fail('.يجب أن يكون تسجيل الدخول بريدًا إلكترونيًا صالحًا أو رقم هاتف');
                    }
                },
            ],
            'password' => 'required|string|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'login_input.required' => '.حقل تسجيل الدخول مطلوب (ادخل البريد الإلكتروني او رقم الهاتف)',
            'login_input.string' => '.يجب أن يكون حقل تسجيل الدخول (الإيميل او الرقم) نصًا صالحًا',
            'password.required' => '.حقل كلمة المرور مطلوب',
            'password.string' => '.يجب أن تكون كلمة المرور نصًا صالحًا',
            'password.min' => '.يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل',
        ];
    }

}