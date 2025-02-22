<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     // Replace this with your own authorization logic if needed
    //     return true;
    // }

    public function rules(): array
    {
        // By default (for POST = store scenario),
        // let's assume these are the base rules:
        $rules = [
            'name' => 'required|string|max:50|unique:roles,name',
            'is_editable' => 'required|boolean',
            'is_deleteable' => 'required|boolean',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|integer|exists:permissions,id',
        ];

        // If the request is PUT/PATCH (an update scenario), we change the rules accordingly
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            // get route parameter (e.g., /roles/{role})
            // If named 'role' in your route, or fallback to 'id'
            $id = $this->route('role') ?? $this->route('id');

            // Only apply name validation if the name is present in the request.
            if ($this->has('name')) {
                $rules['name'] = 'sometimes|required|string|max:50|unique:roles,name,' . $id;
            }            
            // Typically for update:
            // - permissions might not be required (you may allow an update
            //   without changing permissions). So we can remove `required`
            //   or `min:1` if you want. Adjust to your needs:
            $rules['permissions'] = 'sometimes|array';
            $rules['permissions.*'] = 'sometimes|integer|exists:permissions,id';
        }

        return $rules;
    }
}
