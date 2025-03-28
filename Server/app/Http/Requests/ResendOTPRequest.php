<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResendOTPRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '.عنوان البريد الإلكتروني مطلوب',
            'email.email' => '.يجب أن يكون عنوان البريد الإلكتروني بتنسيق بريد إلكتروني صالح',
            'email.exists' => '.عنوان البريد الإلكتروني غير مسجل في نظامنا',
        ];
    }
}
