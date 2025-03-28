<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '.عنوان البريد الإلكتروني مطلوب',
            'email.email' => '.يرجى إدخال عنوان بريد إلكتروني صالح',
            'email.exists' => '.البريد الإلكتروني المدخل غير مسجل',

            'otp.required' => '.رمز التحقق مطلوب',
            'otp.string' => '.يجب أن يكون رمز التحقق نصًا صالحًا',
            'otp.size' => '.يجب أن يتكون رمز التحقق من 6 أحرف بالضبط',
        ];
    }
}
