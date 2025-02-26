<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|in:accepted,rejected',
            'message' => 'required_if:status,rejected',
        ];
    }
}
