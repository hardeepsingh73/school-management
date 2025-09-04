<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // apply your role/permission checks here if needed
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $attendanceId = $this->route('attendance');

        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'class_id'   => ['required', 'exists:school_classes,id'],
            'date'       => ['required', 'date'],
            'recorded_by' => ['nullable', 'exists:teachers,id'],
            'attendance' => ['required', 'array'],
            'attendance.*' => ['required', Rule::in(['present', 'absent'])],

            // Prevent duplicate attendance for same student+subject+date+session
            Rule::unique('attendances')
                ->where(
                    fn($query) => $query
                        ->where('subject_id', $this->subject_id)
                        ->where('class_id', $this->class_id)
                        ->where('date', $this->date)
                )
                ->ignore($attendanceId),
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'subject_id.required' => 'Please select a subject.',
            'class_id.required'   => 'Please select a class.',
            'date.date'           => 'Date must be valid.',
            'unique'              => 'Attendance already recorded for this student in this subject on this date & session.',
        ];
    }
}
