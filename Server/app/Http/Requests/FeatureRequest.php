<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('features')->where(function ($query) {
                    return $query->where('feature_group_id', $this->feature_group_id);
                }),
            ],
            'feature_group_id' => 'required|exists:feature_groups,id',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'The feature name already exists for this group.',
        ];
    }

}
