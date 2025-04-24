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
            'email' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9.]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'exists:users,email'
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'يرجى إدخال عنوان بريدك الإلكتروني.',
            'email.string' => 'يجب أن يكون عنوان البريد الإلكتروني نص صحيح.',
            'email.regex' => 'يرجى إدخال عنوان بريد إلكتروني بتنسيق صحيح.',
            'email.exists' => 'لم نتمكن من العثور على حسابك باستخدام هذا البريد الإلكتروني. يرجى التحقق والمحاولة مرة أخرى.'
        ];
    }
}
