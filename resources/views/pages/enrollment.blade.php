@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :eyebrow="$enrollmentOpen ? 'Student Enrollment' : 'Register Interest'"
        :title="$enrollmentOpen ? 'Apply for the Printing Training School' : 'Register your interest in our training programme'"
        :lead="$enrollmentOpen
            ? 'Enrollment is open for ' . $openPeriod->name . '. Complete the form below to apply for a place.'
            : 'Enrollment is not currently open. Submit your details and we will contact you when the next intake is announced.'"
    />

    @if (! $enrollmentOpen && $upcomingPeriod)
        <section class="py-6 bg-amber-50 border-b border-amber-100">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm text-amber-900">
                    <strong>Next intake:</strong> {{ $upcomingPeriod->name }} opens
                    {{ $upcomingPeriod->opens_at->format('j F Y') }}.
                </p>
            </div>
        </section>
    @endif

    <section
        class="py-16 bg-white text-slate-700"
        x-data="{
            honeypot: '',
            get honeypotFilled() { return this.honeypot.trim().length > 0; },
            invalidate(field, message) {
                field.setCustomValidity(message);
                field.reportValidity();
                field.addEventListener('input', () => field.setCustomValidity(''), { once: true });
            },
            validateForm(event) {
                if (this.honeypotFilled) {
                    event.preventDefault();
                    return;
                }
                const form = event.target;
                const firstName = form.querySelector('[name=\'personal[first_name]\']');
                const lastName = form.querySelector('[name=\'personal[last_name]\']');
                const phone = form.querySelector('[name=\'contact[phone]\']');
                const level = form.querySelector('[name=\'education[level]\']');
                const consent = form.querySelector('[name=\'consent[accepted]\']');

                if (!firstName?.value.trim() || firstName.value.trim().length < 2) {
                    event.preventDefault();
                    this.invalidate(firstName, 'Please enter your first name.');
                    return;
                }
                if (!lastName?.value.trim() || lastName.value.trim().length < 2) {
                    event.preventDefault();
                    this.invalidate(lastName, 'Please enter your last name.');
                    return;
                }
                if (!phone?.value.trim() || phone.value.trim().length < 6) {
                    event.preventDefault();
                    this.invalidate(phone, 'Please enter a valid phone number.');
                    return;
                }
                if (!level?.value) {
                    event.preventDefault();
                    this.invalidate(level, 'Please select your highest qualification.');
                    return;
                }
                if (!consent?.checked) {
                    event.preventDefault();
                    this.invalidate(consent, 'You must accept the privacy terms before submitting.');
                }
            }
        }"
    >
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form
                method="POST"
                action="{{ route('enrollment.store') }}"
                class="quote-request-card p-8 space-y-8"
                @submit="validateForm($event)"
            >
                @csrf

                <input type="text" name="_hp" x-model="honeypot" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <p class="font-semibold mb-1">Please correct the following:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Personal details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">First name *</label>
                            <input type="text" id="first_name" name="personal[first_name]" value="{{ old('personal.first_name') }}" required
                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                        </div>
                        <div>
                            <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Last name *</label>
                            <input type="text" id="last_name" name="personal[last_name]" value="{{ old('personal.last_name') }}" required
                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                        </div>
                        @if ($enrollmentOpen)
                            <div>
                                <label for="date_of_birth" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Date of birth *</label>
                                <input type="date" id="date_of_birth" name="personal[date_of_birth]" value="{{ old('personal.date_of_birth') }}" required
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                            </div>
                            <div>
                                <label for="gender" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Gender</label>
                                <select id="gender" name="personal[gender]" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                                    <option value="">Select</option>
                                    @foreach ($enrollmentConfig['genderOptions'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('personal.gender') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="national_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">National ID *</label>
                                <input type="text" id="national_id" name="personal[national_id]" value="{{ old('personal.national_id') }}" required
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                            </div>
                            <div>
                                <label for="district" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">District</label>
                                <select id="district" name="personal[district]" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                                    <option value="">Select district</option>
                                    @foreach ($enrollmentConfig['districts'] as $district)
                                        <option value="{{ $district }}" @selected(old('personal.district') === $district)>{{ $district }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Physical address *</label>
                                <textarea id="address" name="personal[address]" rows="2" required
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">{{ old('personal.address') }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Contact</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="{{ $enrollmentOpen ? '' : 'sm:col-span-2' }}">
                            <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Phone number *</label>
                            <input type="tel" id="phone" name="contact[phone]" value="{{ old('contact.phone') }}" required
                                placeholder="+265 ..."
                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                        </div>
                        @if ($enrollmentOpen)
                            <div>
                                <label for="emergency_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Emergency contact name *</label>
                                <input type="text" id="emergency_name" name="contact[emergency_name]" value="{{ old('contact.emergency_name') }}" required
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                            </div>
                            <div>
                                <label for="emergency_phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Emergency contact phone *</label>
                                <input type="tel" id="emergency_phone" name="contact[emergency_phone]" value="{{ old('contact.emergency_phone') }}" required
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Education</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="{{ $enrollmentOpen ? '' : 'sm:col-span-2' }}">
                            <label for="education_level" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Highest qualification *</label>
                            <select id="education_level" name="education[level]" required
                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                                <option value="">Select qualification</option>
                                @foreach ($enrollmentConfig['qualificationLevels'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('education.level') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($enrollmentOpen)
                            <div>
                                <label for="institution" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Institution *</label>
                                <input type="text" id="institution" name="education[institution]" value="{{ old('education.institution') }}" required
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                            </div>
                            <div>
                                <label for="year_obtained" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Year obtained *</label>
                                <input type="number" id="year_obtained" name="education[year_obtained]" value="{{ old('education.year_obtained') }}" min="1970" max="{{ now()->year }}" required
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">
                            </div>
                        @endif
                        <div class="sm:col-span-2">
                            <label for="education_details" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Additional details</label>
                            <textarea id="education_details" name="education[details]" rows="2"
                                placeholder="Subjects, grades, or other relevant details"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">{{ old('education.details') }}</textarea>
                        </div>
                    </div>
                </div>

                @if ($enrollmentOpen)
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 mb-4">Experience & motivation</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="prior_experience" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Prior printing experience</label>
                                <textarea id="prior_experience" name="experience[prior_printing_experience]" rows="3"
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">{{ old('experience.prior_printing_experience') }}</textarea>
                            </div>
                            <div>
                                <label for="motivation" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Why do you want to join this programme? *</label>
                                <textarea id="motivation" name="experience[motivation]" rows="3" required
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-forest-500 focus:ring-forest-500">{{ old('experience.motivation') }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="border-t border-slate-100 pt-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="consent[accepted]" value="1" required
                            @checked(old('consent.accepted'))
                            class="mt-1 rounded border-slate-300 text-forest-600 focus:ring-forest-500">
                        <span class="text-sm text-slate-600">
                            I consent to the Department of Printing Services storing my details for the purpose of
                            {{ $enrollmentOpen ? 'processing my enrollment application' : 'recording my interest in the training programme' }}.
                        </span>
                    </label>
                </div>

                <div class="flex flex-wrap gap-4 pt-2">
                    <button type="submit" class="bg-forest-solid inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                        {{ $enrollmentOpen ? 'Submit application' : 'Register interest' }} &rarr;
                    </button>
                    <a href="{{ route('training') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-bold text-slate-600 border border-slate-200">
                        Back to training
                    </a>
                </div>
            </form>
        </div>
    </section>
@endsection
