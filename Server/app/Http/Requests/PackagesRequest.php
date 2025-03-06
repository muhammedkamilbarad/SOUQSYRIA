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
        return [
            'name' => 'required|string|max:100|unique:packages,name,' . $this->route('package'),
            'properties' => 'required|string',
            'price' => 'required|numeric',
            'max_of_ads' => 'required|integer',
            'period' => 'required|integer|min:1', 
        ];
    }
}
