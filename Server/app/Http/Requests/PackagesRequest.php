<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'properties' => 'required|string',
            'price' => 'required|numeric',
            'max_of_ads' => 'required|integer',
        ];
    }
}
