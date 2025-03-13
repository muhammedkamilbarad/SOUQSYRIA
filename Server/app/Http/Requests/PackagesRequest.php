<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackagesRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->isMethod('put') || $this->isMethod('patch')
            ? $this->updateRules()
            : $this->storeRules();
    }

    public function storeRules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:packages,name',
            'properties' => 'required|string',
            'price' => 'required|numeric',
            'max_of_ads' => 'required|integer',
            'period' => 'required|integer|min:1',
        ];
    }

    public function updateRules(): array
    {

        $id = $this->route('package') ?? $this->route('id');

        return [
            'name' => 'required|string|max:100|unique:packages,name,' . $id,
            'properties' => 'required|string',
            'price' => 'required|numeric',
            'max_of_ads' => 'required|integer',
            'period' => 'required|integer|min:1', 
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '.اسم الباقة مطلوب',
            'name.string' => '.يجب أن يكون اسم الباقة نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز اسم الباقة 100 حرف',
            'name.unique' => '.اسم الباقة هذا مستخدم بالفعل. يرجى اختيار اسم آخر',
            'properties.required' => '.حقل الخصائص مطلوب',
            'properties.string' => '.يجب أن يكون حقل الخصائص نصًا صالحًا',
            'price.required' => '.السعر مطلوب',
            'price.numeric' => '.يجب أن يكون السعر رقمًا صالحًا',
            'max_of_ads.required' => '.الحد الأقصى لعدد الإعلانات مطلوب',
            'max_of_ads.integer' => '.يجب أن يكون الحد الأقصى لعدد الإعلانات عددًا صحيحًا',
            'period.required' => '.مدة الباقة مطلوبة',
            'period.integer' => '.يجب أن تكون مدة الباقة عددًا صحيحًا',
            'period.min' => '.يجب ألا تقل مدة الباقة عن 1يوم',
        ];
    }
}
