<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|in:accepted,rejected',
            'message' => 'required_if:status,rejected',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => '.حقل الحالة مطلوب',
            'status.in' => '.يجب أن تكون الحالة إما مقبولة أو مرفوضة',
            'message.required_if' => '.يجب إدخال الرسالة في حالة رفض الطلب',
        ];
    }

}
