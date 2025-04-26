<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Assuming the auth middleware handles authorization
    }

    public function rules()
    {
        return [
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:8',              // Minimum 8 characters
                'max:16',            // Maximum 16 characters
                'confirmed',         // Must match confirmation field
                'regex:/[0-9]/',     // At least one digit
                'regex:/[a-z]/',     // At least one lowercase
                'regex:/[A-Z]/',     // At least one uppercase
                'regex:/[!@#$%^&*(),.?":{}|<>]/' // At least one symbol
            ],
            'new_password_confirmation' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => '.كلمة المرور الحالية مطلوبة',
            'new_password.required' => '.كلمة المرور الجديدة مطلوبة',
            'new_password.min' => '.يجب أن تتكون كلمة المرور الجديدة من 8 أحرف على الأقل',
            'new_password.max' => '.يجب ألا تتجاوز كلمة المرور الجديدة 16 حرفًا',
            'new_password.confirmed' => '.تأكيد كلمة المرور الجديدة غير متطابق',
            'new_password.regex' => '.يجب أن تحتوي كلمة المرور الجديدة على الأقل على رقم واحد، وحرف صغير واحد، وحرف كبير واحد، ورمز خاص واحد',
            'new_password_confirmation.required' => '.تأكيد كلمة المرور مطلوب'
        ];
    }
}
