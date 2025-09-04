<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add permissions if needed
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'class_id'       => ['required', 'exists:school_classes,id'],
            'subject_id'     => ['required', 'exists:subjects,id'],
            'teacher_id'     => ['nullable', 'exists:teachers,id'],

            // prevent duplicate mapping
            Rule::unique('class_subject')->where(
                fn($q) =>
                $q->where('class_id', $this->class_id)
                    ->where('subject_id', $this->subject_id)
            )->ignore($this->route('class_subject'))
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'class_id.required'   => 'Class is required.',
            'subject_id.required' => 'Subject is required.',
            'unique'              => 'This class already has this subject assigned.',
        ];
    }
}
