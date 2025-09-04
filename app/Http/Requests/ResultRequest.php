<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add role/permission check if needed
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'student_id'      => ['required', 'exists:students,id'],
            'exam_id'         => ['required', 'exists:exams,id'],
            'class_id'        => ['required', 'exists:school_classes,id'],
            'total_marks'     => ['nullable', 'numeric', 'min:0'],
            'obtained_marks'  => ['nullable', 'numeric', 'min:0'],
            'grade'           => ['nullable', 'string', 'max:10'],
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'A student must be selected.',
            'student_id.exists'   => 'The selected student does not exist.',
            'exam_id.required'    => 'An exam must be selected.',
            'exam_id.exists'      => 'The selected exam does not exist.',
            'class_id.required'   => 'A class must be selected.',
            'class_id.exists'     => 'The selected class does not exist.',
        ];
    }
}
