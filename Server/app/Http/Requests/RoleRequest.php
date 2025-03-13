<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
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

    public function messages()
    {
        return [
            'name.required' => '.اسم الوظيفة مطلوب',
            'name.string' => '.يجب أن يكون اسم الوظيفة نصًا صالحًا',
            'name.max' => '.يجب ألا يتجاوز اسم الوظيفة 50 حرفًا',
            'name.unique' => '.اسم الوظيفة هذا مستخدم بالفعل',

            'is_editable.required' => '.حالة التحرير (هل يقبل التعديل عليه) مطلوبة',
            'is_editable.boolean' => '.يجب أن تكون حالة التحرير إما صحيحة أو خاطئة',

            'is_deleteable.required' => '.حالة الحذف (هل يقبل الحذف) مطلوبة',
            'is_deleteable.boolean' => '.يجب أن تكون حالة الحذف إما صحيحة أو خاطئة',

            'permissions.required' => 'خانة الإذونات مطلوبة',
            'permissions.array' => '.يجب أن تكون الأذونات مصفوفة',
            'permissions.min' => '.يجب تعيين إذن واحد على الأقل',
            'permissions.*.required' => '.يجب تحديد كل إذن',
            'permissions.*.integer' => '.يجب أن يكون كل إذن عددًا صحيحًا',
            'permissions.*.exists' => '.يجب أن يوجد كل إذن في النظام',
        ];
    }
}
