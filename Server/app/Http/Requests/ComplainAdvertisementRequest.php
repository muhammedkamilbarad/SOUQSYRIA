<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ComplainAdvertisementRequest extends FormRequest
{

    public function rules()
    {
        return [
            'content' => 'required|string',
            'advs_id' => 'required|exists:advertisements,id',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => '.المحتوى مطلوب',
            'content.string' => '.يجب أن يكون المحتوى نصًا صالحًا',
            'advs_id.required' => '.معرّف الإعلان مطلوب',
            'advs_id.exists' => '.الإعلان المحدد غير موجود',
        ];
    }
}
