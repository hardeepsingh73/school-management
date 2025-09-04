<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add Gate/Policy checks here if needed, e.g.:
        // return $this->user()->can('manage departments');
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $departmentId = $this->route('department')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'head_teacher_id' => [
                'nullable',
                'exists:teachers,id',
            ]
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Name
            'name.required' => 'The department name is required.',
            'name.string' => 'The department name must be a string.',
            'name.max' => 'The department name may not exceed 100 characters.',

            // Head Teacher
            'head_teacher_id.exists' => 'The selected head teacher is invalid.',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'department name',
            'head_teacher_id' => 'head teacher',
        ];
    }
}
