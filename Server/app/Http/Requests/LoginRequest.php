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
                'regex:/^[a-zA-Z0-9.]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'exists:users,email'
            ],
            'password' => 'required|string|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'login_input.required' => '.يرجى إدخال عنوان بريدك الإلكتروني',
            'login_input.string' => '.يجب أن يكون عنوان البريد الإلكتروني نص صحيح',
            'login_input.regex' => '.يرجى إدخال عنوان بريد إلكتروني بتنسيق صحيح',
            'login_input.exists' => '.لم نتمكن من العثور على حسابك باستخدام هذا البريد الإلكتروني. يرجى التحقق والمحاولة مرة أخرى',
            'password.required' => '.حقل كلمة المرور مطلوب',
            'password.string' => '.يجب أن تكون كلمة المرور نصًا صالحًا',
            'password.min' => '.يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل',
        ];
    }

}