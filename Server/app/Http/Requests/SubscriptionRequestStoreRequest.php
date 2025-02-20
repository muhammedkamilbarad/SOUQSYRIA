<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequestStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'package_id' => 'required|exists:packages,id',
            'receipt' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
