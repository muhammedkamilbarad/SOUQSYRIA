<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ComplainAdvertisementRequest extends FormRequest
{

    public function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'content' => 'required|string',
            'advs_id' => 'required|exists:advertisements,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '.العنوان مطلوب',
            'title.string' => '.يجب أن يكون العنوان نصًا صالحًا',
            'title.max' => '.يجب ألا يتجاوز العنوان 100 حرف',
            'content.required' => '.المحتوى مطلوب',
            'content.string' => '.يجب أن يكون المحتوى نصًا صالحًا',
            'advs_id.required' => '.معرّف الإعلان مطلوب',
            'advs_id.exists' => '.الإعلان المحدد غير موجود',
        ];
    }
}
