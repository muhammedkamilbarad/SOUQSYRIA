<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    // public function authorize()
    // {
    //     return true;
    // }

    public function rules()
    {
        return $this->isMethod('put') || $this->isMethod('patch')
            ? $this->updateRules()
            : $this->storeRules();
    }
    public function storeRules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9]{7,20}$/|unique:users,phone,',
            'is_verified' => 'boolean',
            'email_verified_at' => 'nullable|date',
            'password' => 'required|string|min:8|max:255',
            'role_id' => 'required|integer|exists:roles,id',
            'image' => 'nullable|string|max:255',
        ];
    }
    private function updateRules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->route('user'),
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9]{7,20}$/|unique:users,phone,' . $this->route('user'),
            'is_verified' => 'boolean',
            'password' => 'nullable|string|min:8|max:255',
            'role_id' => 'required|integer|exists:roles,id',
            'image' => 'nullable|string|max:255',
        ];
    }
}
