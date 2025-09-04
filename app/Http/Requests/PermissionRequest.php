<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Add gate or policy here if needed
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $permissionId = $this->route('permission')?->id ?? null;

        return [
            'name' => [
                'required',
                'min:3',
                Rule::unique('permissions', 'name')->ignore($permissionId),
            ],
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The permission name is required.',
            'name.min' => 'The permission name must be at least :min characters.',
            'name.unique' => 'This permission name is already in use.',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'permission name',
        ];
    }
}
