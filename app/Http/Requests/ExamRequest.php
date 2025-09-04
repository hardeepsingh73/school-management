<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $examId = $this->route('exam');

        return [
            'class_id'         => ['nullable', 'exists:school_classes,id'],
            'subject_id'         => ['nullable', 'exists:subjects,id'],
            'exam_date'            => ['required', 'date'],
            'type'                  => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
            'additional_information' => ['nullable', 'string', 'max:500'],
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'exam_date.required' => 'Exam date is required.',
            'type.in' => 'Invalid exam type.',
        ];
    }
}
