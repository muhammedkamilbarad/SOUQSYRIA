<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
            'message' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '.يرجى إدخال الاسم',
            'name.string'   => '.يجب أن يكون الاسم نصاً صحيحاً',
            'name.max'      => '.يجب ألا يتجاوز الاسم 255 حرفاً',

            'email.required' => '.يرجى إدخال عنوان بريدك الإلكتروني',
            'email.string' => '.يجب أن يكون عنوان البريد الإلكتروني نص صحيح',
            'email.regex' => '.يرجى إدخال عنوان بريد إلكتروني بتنسيق صحيح',
            'email.max' => '.يجب ألا يتجاوز البريد الإلكتروني 255 حرفًا',

            'phone.string' => '.يجب أن يكون رقم الهاتف نصاً صحيحاً',
            'phone.max'    => '.يجب ألا يتجاوز رقم الهاتف 20 حرفاً',
            'phone.regex' => '.يجب أن يكون رقم الهاتف بصيغة صحيحة',

            'message.required' => '.يرجى إدخال الرسالة',
            'message.string'   => '.يجب أن تكون الرسالة نصاً صحيحاً',
            'message.max'      => '.يجب ألا تتجاوز الرسالة 2000 حرف',
        ];
    }
}

