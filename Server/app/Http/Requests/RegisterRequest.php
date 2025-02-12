<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'phone' => 'required|string|max:20|regex:/^\+?[0-9]{7,20}$/|unique:users,phone,',
            'password' => 'required|string|min:8|max:255',
            'confirm_password' => 'required|same:password'
        ];
    }
}
