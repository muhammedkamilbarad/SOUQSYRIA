<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class PopularQuestionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|in:Payment,Subscribtion,Advertisement,System,General',
            'priority' => 'required|in:High,Medium,Low',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => '.حقل السؤال مطلوب',
            'question.string' => '.يجب أن يكون السؤال نصًا صالحًا',
            'question.max' => '.يجب ألا يتجاوز السؤال 255 حرفًا',
            'answer.required' => '.حقل الإجابة مطلوب',
            'answer.string' => '.يجب أن تكون الإجابة نصًا صالحًا',
            'category.required' => '.حقل الفئة (فئة السؤال) مطلوب',
            'category.in' => '.يجب أن تكون الفئة واحدة من التالي: الدفع، الاشتراك، الإعلان، النظام، أو عام',
            'priority.required' => '.حقل الأولوية مطلوب',
            'priority.in' => '.يجب أن تكون الأولوية واحدة من التالي: عالية، متوسطة، أو منخفضة',
            'status.required' => '.حقل الحالة مطلوب',
            'status.boolean' => '.يجب أن تكون الحالة إما فعال أو غير فعال',
        ];
    }
}
