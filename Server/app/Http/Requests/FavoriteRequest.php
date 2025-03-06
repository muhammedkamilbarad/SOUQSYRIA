<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FavoriteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'advs_id' => 'required|exists:advertisements,id',
        ];
    }
}
