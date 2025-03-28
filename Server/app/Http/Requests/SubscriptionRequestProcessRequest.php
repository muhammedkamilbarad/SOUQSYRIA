<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequestProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected',
            'message' => 'required_if:status,rejected',
        ];
    }

    public function messages()
    {
        return [
            'status.required' => '.الحالة مطلوبة',
            'status.in' => '.يجب أن تكون الحالة إما مقبول أو مرفوض',

            'message.required_if' => '.يجب تقديم رسالة عند رفض طلب الاشتراك (رسالة لتبين سبب الرفض)',
        ];
    }
}
