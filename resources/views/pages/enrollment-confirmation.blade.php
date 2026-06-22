@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :eyebrow="$enrollment->type->value === 'enrollment' ? 'Application received' : 'Interest registered'"
        :title="$enrollment->type->value === 'enrollment' ? 'Thank you for your application' : 'Thank you for registering your interest'"
        :lead="$enrollment->type->value === 'enrollment'
            ? 'We have received your enrollment application and will review it shortly.'
            : 'We have recorded your interest. We will contact you by phone when enrollment opens for the next intake.'"
    />

    <section class="py-16 bg-white">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="rounded-xl border border-green-200 bg-green-50 px-6 py-8">
                <p class="text-sm text-slate-600 mb-2">Your reference number</p>
                <p class="text-2xl font-bold text-forest-900 font-display tracking-tight">{{ $enrollment->external_id }}</p>
                <p class="mt-4 text-sm text-slate-600">
                    Please quote this reference in any follow-up communication.
                    @if ($enrollment->phone() !== '—')
                        We have your contact number as <strong>{{ $enrollment->phone() }}</strong>.
                    @endif
                </p>
            </div>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('home') }}" class="btn-brand btn-brand-outline px-6 py-3 text-xs uppercase tracking-wider">Back to home</a>
                <a href="{{ route('training') }}" class="btn-brand btn-brand-solid px-6 py-3 text-xs uppercase tracking-wider">Training page</a>
            </div>
        </div>
    </section>
@endsection
