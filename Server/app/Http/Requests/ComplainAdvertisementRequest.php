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
}
