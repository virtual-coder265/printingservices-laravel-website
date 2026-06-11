<x-guest-layout title="Sign In">

    <div class="space-y-2 mb-8">
        <h2 class="text-2xl font-bold font-display text-slate-900">Sign in to your account</h2>
        <p class="text-sm text-slate-500">Access your quotations, orders, and client dashboard.</p>
    </div>

    <x-auth-session-status class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email address')" class="text-slate-700 font-semibold" />
            <x-text-input
                id="email"
                class="form-input-corporate block mt-2 w-full px-4 py-3"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                inputmode="email"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold" />
            <x-text-input
                id="password"
                class="form-input-corporate block mt-2 w-full px-4 py-3"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-slate-300 text-forest-900 focus:ring-forest-900/20"
                    name="remember"
                >
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-forest-900 hover:underline">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="bg-forest-solid w-full inline-flex items-center justify-center px-5 py-3.5 rounded-lg text-sm font-bold text-white shadow-md">
            {{ __('Sign in') }}
        </button>

        <p class="text-center text-sm text-slate-500">
            {{ __('Need a client account?') }}
            <a href="{{ route('register') }}" class="font-semibold text-forest-900 hover:underline">{{ __('Create one') }}</a>
        </p>
    </form>
</x-guest-layout>
