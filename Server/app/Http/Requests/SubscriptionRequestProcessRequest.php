<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequestProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected',
            'message' => 'required_if:status,rejected',
        ];
    }
}
