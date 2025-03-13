<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequestStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'package_id' => 'required|exists:packages,id',
            'receipt' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'package_id.required' => '.معرف الباقة مطلوب',
            'package_id.exists' => '.معرف الباقة المحدد غير صالح',

            'receipt.required' => '.ملف إيصال الدفع مطلوب',
            'receipt.file' => '.يجب أن يكون الإيصال ملفًا صالحًا',
            'receipt.mimes' => '.يجب أن يكون الإيصال صورة بصيغة (jpeg, png, jpg)',
            'receipt.max' => '.يجب ألا يتجاوز حجم الإيصال 2 ميغابايت',
        ];
    }
}
