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
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
            'message' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'يرجى إدخال الاسم.',
            'name.string'   => 'يجب أن يكون الاسم نصاً صحيحاً.',
            'name.max'      => 'يجب ألا يتجاوز الاسم 255 حرفاً.',

            'email.required' => 'يرجى إدخال عنوان البريد الإلكتروني.',
            'email.email'    => 'يجب أن يكون البريد الإلكتروني بصيغة صحيحة.',
            'email.max'      => 'يجب ألا يتجاوز البريد الإلكتروني 255 حرفاً.',
            'email.dns' => 'يجب أن يكون نطاق البريد الإلكتروني صالحاً.',

            'phone.string' => 'يجب أن يكون رقم الهاتف نصاً صحيحاً.',
            'phone.max'    => 'يجب ألا يتجاوز رقم الهاتف 20 حرفاً.',
            'phone.regex' => 'يجب أن يكون رقم الهاتف بصيغة صحيحة.',

            'message.required' => 'يرجى إدخال الرسالة.',
            'message.string'   => 'يجب أن تكون الرسالة نصاً صحيحاً.',
            'message.max'      => 'يجب ألا تتجاوز الرسالة 2000 حرف.',
        ];
    }
}

