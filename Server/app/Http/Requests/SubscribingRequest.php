<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscribingRequest extends FormRequest
{

    public function rules(): array
    {
        return $this->isMethod('put') || $this->isMethod('patch')
            ? $this->updateRules()
            : $this->storeRules();
    }

    public function storeRules(): array
    {
        return [
            'user_id'    => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            // we are going to set purchase_date, expiry_date, remaining_ads internally
            // so we don't need them from request if your logic doesn't want them from user
        ];
    }

    public function updateRules(): array
    {
        return [
            'package_id' => 'required|exists:packages,id',
            // if "update" in your case means "promote", then we need the new package
        ];
    }
}