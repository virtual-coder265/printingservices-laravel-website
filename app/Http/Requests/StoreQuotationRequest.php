<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $jobType = $this->input('job.type');
        $requiresSize = in_array($jobType, config('quotation_requests.job_types_requiring_size', []), true);
        $requiresPages = in_array($jobType, config('quotation_requests.job_types_requiring_pages', []), true);
        $delivery = $this->input('job.delivery');
        $maxFiles = config('quotation_requests.uploads.max_files', 5);
        $maxKb = config('quotation_requests.uploads.max_size_kb', 10240);

        $rules = [
            '_wizard_step' => ['nullable', 'integer', 'between:1,3'],
            '_hp' => ['nullable', 'max:0'],
            'captcha_token' => [
                Rule::requiredIf(fn () => config('quotation_requests.captcha.required')),
                'nullable',
                'string',
            ],
            'contact.name' => ['required', 'string', 'min:2', 'max:150'],
            'contact.email' => ['required', 'email', 'max:255'],
            'contact.phone' => ['required', 'string', 'min:6', 'max:50'],
            'contact.company' => ['nullable', 'string', 'max:150'],
            'contact.preferred_method' => ['nullable', Rule::in(array_keys(config('quotation_requests.preferred_contact_options')))],
            'job.title' => ['required', 'string', 'max:255'],
            'job.type' => ['required', Rule::in(config('quotation_requests.job_types'))],
            'job.description' => ['required', 'string', 'max:5000'],
            'job.quantity' => ['required', 'integer', 'min:1'],
            'job.required_by' => ['nullable', 'date', 'after_or_equal:today'],
            'job.delivery' => ['nullable', Rule::in(array_keys(config('quotation_requests.delivery_options')))],
            'job.delivery_address' => [
                Rule::requiredIf($delivery === 'delivery'),
                'nullable',
                'string',
                'max:2000',
            ],
            'spec.finished_size_mm.width' => [$requiresSize ? 'required' : 'nullable', 'numeric', 'min:1', 'max:5000'],
            'spec.finished_size_mm.height' => [$requiresSize ? 'required' : 'nullable', 'numeric', 'min:1', 'max:5000'],
            'spec.pages' => [$requiresPages ? 'required' : 'nullable', 'integer', 'min:1'],
            'spec.colours' => ['required', Rule::in(array_keys(config('quotation_requests.colour_options')))],
            'spec.spot_colour_notes' => ['nullable', 'string', 'max:500'],
            'spec.papers' => ['nullable', 'array'],
            'spec.papers.*.role' => ['nullable', Rule::in(array_keys(config('quotation_requests.paper_roles')))],
            'spec.papers.*.type' => ['nullable', 'string', 'max:100'],
            'spec.papers.*.size' => ['nullable', 'string', 'max:50'],
            'spec.papers.*.grammage' => ['nullable', 'integer', 'min:1'],
            'spec.papers.*.color' => ['nullable', 'string', 'max:50'],
            'spec.papers.*.notes' => ['nullable', 'string', 'max:500'],
            'spec.finishing' => ['nullable', 'array'],
            'spec.finishing.*' => [Rule::in(array_keys(config('quotation_requests.finishing_options')))],
            'spec.finishing_notes' => ['nullable', 'string', 'max:1000'],
            'spec.artwork_status' => ['required', Rule::in(array_keys(config('quotation_requests.artwork_status_options')))],
            'spec.budget_hint' => ['nullable', 'string', 'max:255'],
            'spec.banner_environment' => ['nullable', Rule::in(['indoor', 'outdoor'])],
            'spec.binding' => ['nullable', Rule::in(['saddle_stitch', 'perfect_binding', 'wire_o', 'other'])],
            'spec.card_sides' => ['nullable', Rule::in(['single', 'double'])],
            'spec.fold_type' => ['nullable', 'string', 'max:100'],
            'spec.orientation' => ['nullable', Rule::in(['portrait', 'landscape'])],
            'attachments' => ['nullable', 'array', 'max:'.$maxFiles],
            'attachments.*' => [
                'file',
                'max:'.$maxKb,
                'mimetypes:'.implode(',', config('quotation_requests.uploads.allowed_mimes')),
            ],
            'attachments_reference_url' => ['nullable', 'url', 'max:500'],
            'consent.accepted' => ['accepted'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'consent.accepted.accepted' => 'You must accept the privacy terms to submit a quotation request.',
            '_hp.max' => 'Submission could not be processed.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (filled($this->input('_hp'))) {
            $this->merge(['_hp' => 'bot']);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (filled($this->input('_hp'))) {
                $validator->errors()->add('_hp', 'Submission could not be processed.');
            }
        });
    }
}
