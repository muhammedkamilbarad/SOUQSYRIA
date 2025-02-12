<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Check if input matches email or phone format
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    $isPhone = preg_match('/^\+?[0-9]{7,20}$/', $value);
                    
                    if (!$isEmail && !$isPhone) {
                        $fail('The login must be a valid email or phone number.');
                    }
                },
            ],
            'password' => 'required|string|min:8'
        ];
    }
}