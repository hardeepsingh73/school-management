<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SchoolClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // add role/permission checks if needed
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $classId = $this->route('school_class');

        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'section' => [
                'nullable',
                'string',
                'max:10',
            ],
            'class_teacher_id' => [
                'nullable',
                'exists:teachers,id',
            ],
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please provide the class name.',
            'class_teacher_id.exists'   => 'The selected class teacher does not exist.',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'class name',
            'section' => 'section',
            'class_teacher_id' => 'class teacher',
        ];
    }
}
