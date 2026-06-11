<x-guest-layout title="Register">

    <div class="space-y-2 mb-8">
        <h2 class="text-2xl font-bold font-display text-slate-900">Create your client account</h2>
        <p class="text-sm text-slate-500">Register to request quotations, place orders, and track your printing projects.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
        @csrf

        <div class="quote-honeypot" aria-hidden="true">
            <label for="website">Website</label>
            <input type="text" name="website" id="website" value="" tabindex="-1" autocomplete="off">
        </div>

        <div>
            <x-input-label for="name" :value="__('Full name')" class="text-slate-700 font-semibold" />
            <x-text-input
                id="name"
                class="form-input-corporate block mt-2 w-full px-4 py-3"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                maxlength="255"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email address')" class="text-slate-700 font-semibold" />
            <x-text-input
                id="email"
                class="form-input-corporate block mt-2 w-full px-4 py-3"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                inputmode="email"
                maxlength="255"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone number (optional)')" class="text-slate-700 font-semibold" />
            <x-text-input
                id="phone"
                class="form-input-corporate block mt-2 w-full px-4 py-3"
                type="tel"
                name="phone"
                :value="old('phone')"
                autocomplete="tel"
                inputmode="tel"
                maxlength="30"
                placeholder="+265 ..."
            />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold" />
            <x-text-input
                id="password"
                class="form-input-corporate block mt-2 w-full px-4 py-3"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <p class="mt-2 text-xs text-slate-500">
                At least 10 characters with uppercase, lowercase, a number, and a symbol.
            </p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" class="text-slate-700 font-semibold" />
            <x-text-input
                id="password_confirmation"
                class="form-input-corporate block mt-2 w-full px-4 py-3"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <label for="terms_accepted" class="flex items-start gap-3 text-sm text-slate-600">
                <input
                    id="terms_accepted"
                    type="checkbox"
                    name="terms_accepted"
                    value="1"
                    @checked(old('terms_accepted'))
                    required
                    class="mt-1 rounded border-slate-300 text-forest-900 focus:ring-forest-900/20"
                >
                <span>
                    I agree to use this account for legitimate printing service enquiries and accept the
                    <a href="{{ asset(config('homepage.utility.charter_file', 'documents/service-charter.pdf')) }}" class="font-semibold text-forest-900 hover:underline" target="_blank" rel="noopener">service charter</a>
                    and client account terms.
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms_accepted')" class="mt-2" />
        </div>

        <button type="submit" class="bg-forest-solid w-full inline-flex items-center justify-center px-5 py-3.5 rounded-lg text-sm font-bold text-white shadow-md">
            {{ __('Create account') }}
        </button>

        <p class="text-center text-sm text-slate-500">
            {{ __('Already registered?') }}
            <a href="{{ route('login') }}" class="font-semibold text-forest-900 hover:underline">{{ __('Sign in') }}</a>
        </p>
    </form>
</x-guest-layout>
