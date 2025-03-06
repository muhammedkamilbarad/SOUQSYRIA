<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
{

    public function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'content' => 'required|string',
        ];
    }
}
