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
}
