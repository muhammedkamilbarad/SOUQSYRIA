<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureGroupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return $this->isMethod('put') || $this->isMethod('patch')
            ? $this->updateRules()
            : $this->storeRules();
    }

    public function storeRules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('feature_groups')->where(function ($query) {
                    return $query->where('category_id', $this->category_id);
                }),
            ],
            'category_id' => 'required|exists:categories,id',
            'features' => 'array|min:1',
            'features.*' => 'string',
        ];
    }

    public function updateRules()
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('feature_groups')->where(function ($query) {
                    return $query->where('category_id', $this->category_id);
                })->ignore($this->route('feature_group'))
            ],
            'category_id' => 'required|exists:categories,id',
            'features' => 'array|min:1',
            'features.*' => 'string',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'The feature group name already exists for this category.',
        ];
    }

}
