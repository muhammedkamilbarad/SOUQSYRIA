<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    public function rules()
    {
        return $this->isMethod('put') || $this->isMethod('patch')
            ? $this->updateRules()
            : $this->storeRules();
    }
    public function storeRules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'max:255',
                'unique:users,email,'
            ],
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9 ]{7,20}$/|unique:users,phone,',
            'is_verified' => 'boolean',
            'email_verified_at' => 'nullable|date',
            'password' => 'required|string|min:8|max:255',
            'role_id' => 'required|integer|exists:roles,id',
            'image' => 'nullable|string|max:255',
        ];
    }
    private function updateRules()
    {
        $id = $this->route('role') ?? $this->route('id');

        return [
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9.]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'max:255',
                'unique:users,email,' . $id
            ],
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9 ]{7,20}$/|unique:users,phone,' . $id,
            'is_verified' => 'boolean',
            'password' => 'nullable|string|min:8|max:255',
            'role_id' => 'required|integer|exists:roles,id',
            'image' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '.الاسم مطلوب',
            'name.string' => '.يجب أن يكون الاسم نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز الاسم 100 حرف',

            'email.required' => '.البريد الإلكتروني مطلوب',
            'email.string' => '.يجب أن يكون البريد الإلكتروني نصًا صالحًا',
            'email.regex' => 'يرجى إدخال عنوان بريد إلكتروني بتنسيق صحيح.',
            'email.max' => '.يجب ألا يتجاوز البريد الإلكتروني 255 حرفًا',
            'email.unique' => '.هذا البريد الإلكتروني مستخدم بالفعل',

            'phone.string' => '.يجب أن يكون رقم الهاتف رقماً صالحًا',
            'phone.max' => '.يجب ألا يتجاوز رقم الهاتف 20 رقماً',
            'phone.regex' => '.تنسيق رقم الهاتف غير صالح',
            'phone.unique' => '.رقم الهاتف هذا مستخدم بالفعل',

            'is_verified.boolean' => '.يجب أن تكون حالة التحقق صحيحة أو خاطئة',

            'email_verified_at.date' => '.يجب أن يكون تاريخ التحقق من البريد الإلكتروني تاريخًا صالحًا',

            'password.required' => '.كلمة المرور مطلوبة',
            'password.string' => '.يجب أن تكون كلمة المرور نصًا صالحًا',
            'password.min' => '.يجب أن تتكون كلمة المرور من 8 أحرف على الأقل',
            'password.max' => '.يجب ألا تتجاوز كلمة المرور 255 حرفًا',

            'role_id.required' => '.خانة الدور مطلوبة',
            'role_id.integer' => '.يجب أن يكون معرف الدور رقمًا صحيحًا',
            'role_id.exists' => '.معرف الدور المحدد غير صالح',

            'image.string' => '.يجب أن رابط الصورة نصًا صالحًا',
            'image.max' => '.يجب ألا يتجاوز حجم الصورة 255 حرفًا',
        ];
    }
}
