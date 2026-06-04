@extends('layouts.public')

@php
    $oldPapers = old('spec.papers', []);
    $paperRowsDefault = count($oldPapers) > 0 ? $oldPapers : [
        ['role' => 'cover', 'type' => '', 'size' => '', 'grammage' => '', 'color' => '', 'notes' => ''],
    ];
    $initialStep = $errors->any() ? (int) old('_wizard_step', 1) : 1;
@endphp

@section('content')
    <x-public.page-hero
        eyebrow="Request a Quotation"
        title="Tell us about your print job"
        lead="Submit a structured brief in three steps. No account required — we will respond with guidance on scope and timing."
    />

    <section
        class="py-16 bg-white text-slate-700"
        x-data="{
            step: {{ $initialStep }},
            honeypot: '',
            jobType: @js(old('job.type', '')),
            jobDelivery: @js(old('job.delivery', '')),
            stepError: '',
            jobTypesRequiringSize: @js($quoteConfig['jobTypesRequiringSize']),
            jobTypesRequiringPages: @js($quoteConfig['jobTypesRequiringPages']),
            paperRows: @js($paperRowsDefault),
            get honeypotFilled() { return this.honeypot.trim().length > 0; },
            requiresSize() { return this.jobTypesRequiringSize.includes(this.jobType); },
            requiresPages() { return this.jobTypesRequiringPages.includes(this.jobType); },
            isBooklet() { return this.jobType === 'Booklet'; },
            isBanner() { return this.jobType === 'Banner'; },
            isBusinessCards() { return this.jobType === 'Business Cards'; },
            fieldValue(el, name) {
                const field = el.querySelector('[name=\'' + name + '\']');
                return field ? String(field.value ?? '').trim() : '';
            },
            invalidate(field, message) {
                field.setCustomValidity(message);
                field.reportValidity();
                field.addEventListener('input', () => field.setCustomValidity(''), { once: true });
            },
            validateStep(stepNum) {
                const el = this.$refs['step' + stepNum];
                if (!el) return false;
                this.stepError = '';

                if (stepNum === 1) {
                    const name = el.querySelector('[name=\'contact[name]\']');
                    const email = el.querySelector('[name=\'contact[email]\']');
                    const phone = el.querySelector('[name=\'contact[phone]\']');
                    if (!name?.value.trim() || name.value.trim().length < 2) {
                        this.invalidate(name, 'Please enter your full name (at least 2 characters).');
                        return false;
                    }
                    if (!email?.value.trim() || !email.checkValidity()) {
                        this.invalidate(email, 'Please enter a valid email address.');
                        return false;
                    }
                    if (!phone?.value.trim() || phone.value.trim().length < 6) {
                        this.invalidate(phone, 'Please enter a valid phone number.');
                        return false;
                    }
                    return true;
                }

                if (stepNum === 2) {
                    const title = el.querySelector('[name=\'job[title]\']');
                    const type = el.querySelector('[name=\'job[type]\']');
                    const desc = el.querySelector('[name=\'job[description]\']');
                    const qty = el.querySelector('[name=\'job[quantity]\']');
                    if (!title?.value.trim()) { this.invalidate(title, 'Job title is required.'); return false; }
                    if (!type?.value) { this.invalidate(type, 'Please select a job type.'); return false; }
                    if (!desc?.value.trim()) { this.invalidate(desc, 'Please describe your job.'); return false; }
                    if (!qty?.value || Number(qty.value) < 1) { this.invalidate(qty, 'Quantity must be at least 1.'); return false; }
                    if (this.requiresPages()) {
                        const pages = el.querySelector('[name=\'spec[pages]\']');
                        if (!pages?.value || Number(pages.value) < 1) {
                            this.invalidate(pages, 'Number of pages is required for this job type.');
                            return false;
                        }
                    }
                    if (this.jobDelivery === 'delivery') {
                        const addr = el.querySelector('[name=\'job[delivery_address]\']');
                        if (!addr?.value.trim()) {
                            this.invalidate(addr, 'Delivery address is required when delivery is selected.');
                            return false;
                        }
                    }
                    return true;
                }

                if (stepNum === 3) {
                    if (this.requiresSize()) {
                        const w = el.querySelector('[name=\'spec[finished_size_mm][width]\']');
                        const h = el.querySelector('[name=\'spec[finished_size_mm][height]\']');
                        if (!w?.value || Number(w.value) < 1) {
                            this.invalidate(w, 'Finished width (mm) is required.');
                            return false;
                        }
                        if (!h?.value || Number(h.value) < 1) {
                            this.invalidate(h, 'Finished height (mm) is required.');
                            return false;
                        }
                    }
                    const colours = el.querySelector('[name=\'spec[colours]\']');
                    const artwork = el.querySelector('[name=\'spec[artwork_status]\']');
                    const consent = el.querySelector('[name=\'consent[accepted]\']');
                    if (!colours?.value) { this.invalidate(colours, 'Please select a colour option.'); return false; }
                    if (!artwork?.value) { this.invalidate(artwork, 'Please select artwork status.'); return false; }
                    if (!consent?.checked) {
                        this.stepError = 'You must accept the privacy terms before submitting.';
                        consent?.focus();
                        return false;
                    }
                    return true;
                }

                return true;
            },
            nextStep() {
                if (!this.validateStep(this.step)) return;
                if (this.step < 3) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },
            prevStep() {
                this.stepError = '';
                if (this.step > 1) {
                    this.step--;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },
            onSubmit(event) {
                if (this.honeypotFilled) {
                    event.preventDefault();
                    return;
                }
                for (let s = 1; s <= 3; s++) {
                    if (!this.validateStep(s)) {
                        event.preventDefault();
                        this.step = s;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }
                }
            },
            addPaperRow() {
                this.paperRows.push({ role: 'text', type: '', size: '', grammage: '', color: '', notes: '' });
            },
        }"
    >
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-8 flex items-center justify-between gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                <span :class="step >= 1 ? 'text-forest-900' : ''">1. Your details</span>
                <span :class="step >= 2 ? 'text-forest-900' : ''">2. Your job</span>
                <span :class="step >= 3 ? 'text-forest-900' : ''">3. Specs &amp; files</span>
            </div>

            <div class="quote-request-card relative">
                <form
                    action="{{ route('quotation.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="p-6 space-y-5"
                    novalidate
                    @submit="onSubmit($event)"
                >
                    @csrf
                    <input type="hidden" name="_wizard_step" :value="step">

                    <div class="quote-honeypot" aria-hidden="true">
                        <label for="quote_website_url">Website</label>
                        <input
                            type="text"
                            id="quote_website_url"
                            name="_hp"
                            x-model="honeypot"
                            tabindex="-1"
                            autocomplete="off"
                            inputmode="none"
                        >
                    </div>

                    <p x-show="stepError" x-text="stepError" class="text-sm text-red-700 rounded-lg border border-red-200 bg-red-50 px-3 py-2" style="display: none"></p>

                    {{-- Step 1: Contact --}}
                    <div x-ref="step1" x-show="step === 1" class="space-y-4" @if ($initialStep === 1) style="display: block" @endif>
                        <input type="text" name="contact[name]" value="{{ old('contact.name') }}" placeholder="Full name *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                        <input type="email" name="contact[email]" value="{{ old('contact.email') }}" placeholder="Email *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                        <input type="tel" name="contact[phone]" value="{{ old('contact.phone') }}" placeholder="Phone *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                        <input type="text" name="contact[company]" value="{{ old('contact.company') }}" placeholder="Company / department" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                        <select name="contact[preferred_method]" class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                            <option value="">Preferred contact method</option>
                            @foreach ($quoteConfig['preferredContactOptions'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('contact.preferred_method') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="button" @click="nextStep()" class="btn-brand btn-brand-solid w-full px-4 py-3 text-xs tracking-wider uppercase">Continue &rarr;</button>
                    </div>

                    {{-- Step 2: Job --}}
                    <div x-ref="step2" x-show="step === 2" class="space-y-4" style="display: none">
                        <input type="text" name="job[title]" value="{{ old('job.title') }}" placeholder="Job title *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                        <select name="job[type]" x-model="jobType" class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                            <option value="">Job type *</option>
                            @foreach ($quoteConfig['jobTypes'] as $type)
                                <option value="{{ $type }}" @selected(old('job.type') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        <textarea name="job[description]" rows="4" placeholder="Describe the job, content, and special instructions *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">{{ old('job.description') }}</textarea>
                        <input type="number" name="job[quantity]" value="{{ old('job.quantity', 1) }}" min="1" placeholder="Quantity (copies / sets) *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                        <input type="date" name="job[required_by]" value="{{ old('job.required_by') }}" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                        <select name="job[delivery]" x-model="jobDelivery" class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                            <option value="">Delivery preference</option>
                            @foreach ($quoteConfig['deliveryOptions'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('job.delivery') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <textarea name="job[delivery_address]" rows="2" placeholder="Delivery address (required if delivery selected)" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">{{ old('job.delivery_address') }}</textarea>

                        <template x-if="requiresPages()">
                            <input type="number" name="spec[pages]" value="{{ old('spec.pages') }}" min="1" placeholder="Number of pages *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                        </template>
                        <template x-if="isBooklet()">
                            <select name="spec[binding]" class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                                <option value="">Binding</option>
                                @foreach (['saddle_stitch' => 'Saddle stitch', 'perfect_binding' => 'Perfect binding', 'wire_o' => 'Wire-O', 'other' => 'Other'] as $val => $lab)
                                    <option value="{{ $val }}" @selected(old('spec.binding') === $val)>{{ $lab }}</option>
                                @endforeach
                            </select>
                        </template>
                        <template x-if="isBanner()">
                            <select name="spec[banner_environment]" class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                                <option value="">Indoor / outdoor</option>
                                <option value="indoor" @selected(old('spec.banner_environment') === 'indoor')>Indoor</option>
                                <option value="outdoor" @selected(old('spec.banner_environment') === 'outdoor')>Outdoor</option>
                            </select>
                        </template>
                        <template x-if="isBusinessCards()">
                            <select name="spec[card_sides]" class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                                <option value="">Card sides</option>
                                <option value="single" @selected(old('spec.card_sides') === 'single')>Single sided</option>
                                <option value="double" @selected(old('spec.card_sides') === 'double')>Double sided</option>
                            </select>
                        </template>

                        <div class="flex gap-3">
                            <button type="button" @click="prevStep()" class="btn-brand btn-brand-outline flex-1 px-4 py-3 text-xs uppercase tracking-wider">&larr; Back</button>
                            <button type="button" @click="nextStep()" class="btn-brand btn-brand-solid flex-1 px-4 py-3 text-xs uppercase tracking-wider">Continue &rarr;</button>
                        </div>
                    </div>

                    {{-- Step 3: Spec & files --}}
                    <div x-ref="step3" x-show="step === 3" class="space-y-4" style="display: none">
                        <template x-if="requiresSize()">
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" name="spec[finished_size_mm][width]" value="{{ old('spec.finished_size_mm.width') }}" step="0.1" min="1" placeholder="Width (mm) *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                                <input type="number" name="spec[finished_size_mm][height]" value="{{ old('spec.finished_size_mm.height') }}" step="0.1" min="1" placeholder="Height (mm) *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                            </div>
                        </template>

                        <select name="spec[colours]" class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                            <option value="">Colour *</option>
                            @foreach ($quoteConfig['colourOptions'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('spec.colours') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="spec[spot_colour_notes]" value="{{ old('spec.spot_colour_notes') }}" placeholder="Spot colour details (if applicable)" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">

                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-slate-600">Paper stocks (optional)</p>
                            <template x-for="(row, index) in paperRows" :key="index">
                                <div class="grid grid-cols-2 gap-2 p-3 rounded border border-slate-200 bg-slate-50">
                                    <select :name="'spec[papers][' + index + '][role]'" x-model="row.role" class="form-input-corporate col-span-2 text-xs">
                                        @foreach ($quoteConfig['paperRoles'] as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" :name="'spec[papers][' + index + '][type]'" x-model="row.type" placeholder="Type" class="form-input-corporate text-xs">
                                    <input type="text" :name="'spec[papers][' + index + '][size]'" x-model="row.size" placeholder="Size" class="form-input-corporate text-xs">
                                    <input type="number" :name="'spec[papers][' + index + '][grammage]'" x-model="row.grammage" placeholder="gsm" class="form-input-corporate text-xs">
                                    <input type="text" :name="'spec[papers][' + index + '][color]'" x-model="row.color" placeholder="Colour" class="form-input-corporate text-xs">
                                </div>
                            </template>
                            <button type="button" @click="addPaperRow()" class="text-xs text-forest-800 font-semibold hover:underline">+ Add paper line</button>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-slate-600 mb-2">Finishing (optional)</p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                @foreach ($quoteConfig['finishingOptions'] as $value => $label)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="spec[finishing][]" value="{{ $value }}" @checked(in_array($value, old('spec.finishing', [])))>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <input type="text" name="spec[finishing_notes]" value="{{ old('spec.finishing_notes') }}" placeholder="Other finishing notes" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">

                        <select name="spec[artwork_status]" class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                            <option value="">Artwork status *</option>
                            @foreach ($quoteConfig['artworkStatusOptions'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('spec.artwork_status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="spec[budget_hint]" value="{{ old('spec.budget_hint') }}" placeholder="Budget indication (optional)" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Upload files (max {{ $quoteConfig['maxFiles'] }}, {{ $quoteConfig['maxFileSizeMb'] }} MB each)</label>
                            <input type="file" name="attachments[]" multiple class="mt-1 block w-full text-xs text-slate-600">
                        </div>
                        <input type="url" name="attachments_reference_url" value="{{ old('attachments_reference_url') }}" placeholder="Link to files (Google Drive, etc.)" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">

                        <label class="flex items-start gap-2 text-xs">
                            <input type="checkbox" name="consent[accepted]" value="1" @checked(old('consent.accepted')) class="mt-0.5">
                            <span>I agree to the processing of my details for this quotation request. *</span>
                        </label>

                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="prevStep()" class="btn-brand btn-brand-outline flex-1 px-4 py-3 text-xs uppercase tracking-wider">&larr; Back</button>
                            <button
                                type="submit"
                                class="btn-brand btn-brand-solid flex-1 px-4 py-3 text-xs uppercase tracking-wider disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="honeypotFilled"
                                :title="honeypotFilled ? 'Submission blocked' : ''"
                            >Submit request &rarr;</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
