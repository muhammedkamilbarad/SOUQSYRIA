<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Anyone can request a password reset
    }

    public function rules()
    {
        return [
            'token' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'confirmed',         // Must match confirmation field
                'regex:/[0-9]/',     // At least one digit
                'regex:/[a-z]/',     // At least one lowercase
                'regex:/[A-Z]/',     // At least one uppercase
                'regex:/[!@#$%^&*(),.?":{}|<>]/' // At least one symbol
            ],
            'password_confirmation' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'token.required' => '.حقل الرمز مطلوب',

            'password.required' => '.حقل كلمة المرور مطلوب',
            'password.string' => '.يجب أن تكون كلمة المرور نصًا صالحًا',
            'password.min' => '.يجب أن تكون كلمة المرور على الأقل 8 أحرف',
            'password.max' => '.يجب ألا تتجاوز كلمة المرور 16 حرفًا',
            'password.confirmed' => '.تأكيد كلمة المرور غير متطابق',
            'password.regex' => '.يجب أن تحتوي كلمة المرور على حرف كبير واحد على الأقل، وحرف صغير واحد على الأقل، ورقم واحد على الأقل، ورمز خاص واحد على الأقل',

            'password_confirmation.required' => '.حقل تأكيد كلمة المرور مطلوب',
        ];
    }
}
