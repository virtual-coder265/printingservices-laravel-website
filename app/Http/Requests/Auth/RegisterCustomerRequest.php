<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class RegisterCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-\'\.]+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[\d\s\(\)\+\-\.]+$/'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms_accepted' => ['accepted'],
            'website' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($this->filled('website')) {
                $validator->errors()->add('email', 'We could not process your registration. Please try again.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Please enter a valid full name using letters only.',
            'phone.regex' => 'Please enter a valid phone number.',
            'terms_accepted.accepted' => 'You must accept the client account terms to register.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    public function attributes(): array
    {
        return [
            'terms_accepted' => 'terms and conditions',
        ];
    }
}
