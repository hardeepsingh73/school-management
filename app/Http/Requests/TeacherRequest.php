<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
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
        $teacherId = $this->route('teacher')?->user_id ?? null;

        return [

            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($teacherId),
            ],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
            'gender' => [
                'nullable',
                Rule::in(['male', 'female', 'other', 'unspecified']),
            ],
            'dob' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'string', 'max:191'],
            'blood_group' => [
                'nullable',
                Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['nullable', 'integer'],
            'profile_image' => [
                'nullable',
                'image',
                'max:2048',
            ],
            'additional_information' => ['nullable', 'string', 'max:500'],
            'department_id' => ['required', 'exists:departments,id'],
            'designation' => ['nullable', 'string', 'max:50'],
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Student name is required.',
            'email.required' => 'Student email is required.',
            'password.required' => 'Password is required for new students.',
            'password.confirmed' => 'Password confirmation does not match.',
            'profile_image.image' => 'The profile image must be an image file.',
            'profile_image.max' => 'The profile image size may not be greater than :max kilobytes.',
            'department_id.required' => 'The department is required.',
            'department_id.exists' => 'Selected department does not exist.',

            'designation.string' => 'The designation must be a string.',
            'designation.max' => 'The designation may not be greater than 50 characters.',

        ];
    }
}
