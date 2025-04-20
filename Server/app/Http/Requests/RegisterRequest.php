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
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'phone' => 'required|string|max:20|regex:/^\+?[0-9 ]{7,20}$/|unique:users,phone',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/',
                'same:confirm_password'
            ],
            'confirm_password' => 'required'
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
            'email.regex' => 'يرجى إدخال عنوان بريد إلكتروني بتنسيق صحيح.',
            'email.max' => '.يجب ألا يتجاوز عنوان البريد الإلكتروني 255 حرفًا',
            'email.unique' => '.عنوان البريد الإلكتروني هذا مستخدم بالفعل',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => '.يجب أن يكون رقم الهاتف نصًا صالحًا',
            'phone.max' => '.يجب ألا يتجاوز رقم الهاتف 20 رقماً',
            'phone.regex' => '.يجب أن يكون رقم الهاتف بتنسيق صالح، مع أو بدون رمز البلد',
            'phone.unique' => '.رقم الهاتف هذا مستخدم بالفعل',

            'password.required' => '.حقل كلمة المرور مطلوب',
            'password.string' => '.يجب أن تكون كلمة المرور نصًا صالحًا',
            'password.min' => '.يجب أن تكون كلمة المرور على الأقل 8 أحرف',
            'password.max' => '.يجب ألا تتجاوز كلمة المرور 16 حرفًا',
            'password.same' => '.تأكيد كلمة المرور غير متطابق',
            'password.regex' => '.يجب أن تحتوي كلمة المرور على حرف كبير واحد على الأقل، وحرف صغير واحد على الأقل، ورقم واحد على الأقل، ورمز خاص واحد على الأقل',

            'confirm_password.required' => 'حقل تأكيد كلمة المرور مطلوب.',
        ];
    }
}
