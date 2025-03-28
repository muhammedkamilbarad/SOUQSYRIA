<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9 ]{7,20}$/|unique:users,phone,',
            'password' => 'required|string|min:8|max:255',
            'confirm_password' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '.الاسم مطلوب',
            'name.string' => '.يجب أن يكون الاسم نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز الاسم 100 حرف',

            'email.required' => '.عنوان البريد الإلكتروني مطلوب',
            'email.string' => '.يجب أن يكون البريد الإلكتروني نصًا صالحًا',
            'email.email' => '.يجب أن يكون عنوان البريد الإلكتروني بتنسيق بريد إلكتروني صالح',
            'email.max' => '.يجب ألا يتجاوز عنوان البريد الإلكتروني 255 حرفًا',
            'email.unique' => '.عنوان البريد الإلكتروني هذا مستخدم بالفعل',

            'phone.string' => '.يجب أن يكون رقم الهاتف نصًا صالحًا',
            'phone.max' => '.يجب ألا يتجاوز رقم الهاتف 20 رقماً',
            'phone.regex' => '.يجب أن يكون رقم الهاتف بتنسيق صالح، مع أو بدون رمز البلد',
            'phone.unique' => '.رقم الهاتف هذا مستخدم بالفعل',

            'password.required' => '.كلمة المرور مطلوبة',
            'password.string' => '.يجب أن تكون كلمة المرور نصًا صالحًا',
            'password.min' => '.يجب أن تكون كلمة المرور مكونة من 8 أحرف على الأقل',
            'password.max' => '.يجب ألا تتجاوز كلمة المرور 255 حرفًا',

            'confirm_password.required' => '.تأكيد كلمة المرور مطلوب',
            'confirm_password.same' => '.تأكيد كلمة المرور لا يتطابق مع كلمة المرور',
        ];
    }
}
