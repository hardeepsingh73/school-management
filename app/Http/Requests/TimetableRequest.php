<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TimetableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // add role/permission if needed
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'class_id'         => ['required', 'exists:school_classes,id'],
            'teacher_id'       => ['required', 'exists:teachers,id'],
            'subject_id'       => ['required', 'exists:subjects,id'],
            'day_of_week'      => ['required', 'integer', 'between:0,6'],
            'start_time'       => ['required', 'date_format:H:i'],
            'end_time'         => ['required', 'date_format:H:i', 'after:start_time'],
            'room'             => ['nullable', 'string', 'max:20'],
            'schedule_type'    => ['required', 'integer', Rule::in([1, 2, 3])],
            'effective_from'   => ['nullable', 'date'],
            'effective_until'  => ['nullable', 'date', 'after_or_equal:effective_from'],
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'class_id.required' => 'Please select a class.',
            'teacher_id.required' => 'Please select a teacher.',
            'subject_id.required' => 'Please select a subject.',
            'day_of_week.between' => 'Day of week must be between 0 (Sunday) and 6 (Saturday).',
            'end_time.after' => 'End time must be after start time.',
            'effective_until.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'class_id'         => 'class',
            'teacher_id'       => 'teacher',
            'subject_id'       => 'subject',
            'day_of_week'      => 'day of week',
            'start_time'       => 'start time',
            'end_time'         => 'end time',
            'room'             => 'room',
            'schedule_type'    => 'schedule type',
            'effective_from'   => 'effective from date',
            'effective_until'  => 'effective until date',
        ];
    }
}
