<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Anyone can request a password reset
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email|exists:users,email'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'يرجى إدخال عنوان بريدك الإلكتروني.',
            'email.string' => 'يجب أن يكون عنوان البريد الإلكتروني نص صحيح.',
            'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صالح.',
            'email.exists' => 'لم نتمكن من العثور على حسابك باستخدام هذا البريد الإلكتروني. يرجى التحقق والمحاولة مرة أخرى.'
        ];
    }
}
