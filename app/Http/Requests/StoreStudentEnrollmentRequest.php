<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $isOpen = app(\App\Services\StudentEnrollments\RegistrationCalendarService::class)->isEnrollmentOpen();
        $this->attributes->set('enrollment_open', $isOpen);
    }

    public function rules(): array
    {
        $isEnrollmentOpen = (bool) $this->attributes->get('enrollment_open');

        $rules = [
            '_hp' => ['nullable', 'max:0'],
            'personal.first_name' => ['required', 'string', 'min:2', 'max:100'],
            'personal.last_name' => ['required', 'string', 'min:2', 'max:100'],
            'personal.date_of_birth' => [$isEnrollmentOpen ? 'required' : 'nullable', 'date', 'before:today', 'after:1950-01-01'],
            'personal.gender' => ['nullable', Rule::in(array_keys(config('student_enrollments.gender_options')))],
            'personal.national_id' => [$isEnrollmentOpen ? 'required' : 'nullable', 'string', 'max:50'],
            'personal.district' => ['nullable', Rule::in(config('student_enrollments.districts'))],
            'personal.address' => [$isEnrollmentOpen ? 'required' : 'nullable', 'string', 'max:500'],
            'contact.phone' => ['required', 'string', 'min:6', 'max:50'],
            'contact.emergency_name' => [$isEnrollmentOpen ? 'required' : 'nullable', 'string', 'max:150'],
            'contact.emergency_phone' => [$isEnrollmentOpen ? 'required' : 'nullable', 'string', 'min:6', 'max:50'],
            'education.level' => ['required', Rule::in(array_keys(config('student_enrollments.qualification_levels')))],
            'education.institution' => [$isEnrollmentOpen ? 'required' : 'nullable', 'string', 'max:200'],
            'education.year_obtained' => [$isEnrollmentOpen ? 'required' : 'nullable', 'integer', 'min:1970', 'max:'.now()->year],
            'education.details' => ['nullable', 'string', 'max:1000'],
            'experience.prior_printing_experience' => ['nullable', 'string', 'max:2000'],
            'experience.motivation' => [$isEnrollmentOpen ? 'required' : 'nullable', 'string', 'max:2000'],
            'consent.accepted' => ['accepted'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'personal.first_name.required' => 'Please enter your first name.',
            'personal.last_name.required' => 'Please enter your last name.',
            'contact.phone.required' => 'Please enter your contact phone number.',
            'education.level.required' => 'Please select your highest qualification.',
            'consent.accepted.accepted' => 'You must accept the privacy terms before submitting.',
        ];
    }
}
