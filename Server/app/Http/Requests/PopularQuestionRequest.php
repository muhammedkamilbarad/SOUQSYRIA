<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class PopularQuestionRequest extends FormRequest
{
    // public function authorize()
    // {
    //     return true;
    // }

    public function rules()
    {
        return [
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
        ];
    }
}
