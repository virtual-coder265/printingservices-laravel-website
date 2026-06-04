@extends('layouts.public')

@section('content')
    <x-public.page-hero
        eyebrow="Quotation received"
        title="Thank you for your request"
        lead="We have received your print job brief and will review it shortly."
    />

    <section class="py-16 bg-white">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="rounded-xl border border-green-200 bg-green-50 px-6 py-8">
                <p class="text-sm text-slate-600 mb-2">Your reference number</p>
                <p class="text-2xl font-bold text-forest-900 font-display tracking-tight">{{ $quotationRequest->external_id }}</p>
                <p class="mt-4 text-sm text-slate-600">
                    Please quote this reference in any follow-up communication. A confirmation has been recorded for
                    <strong>{{ $quotationRequest->contactEmail() }}</strong>.
                </p>
            </div>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('home') }}" class="btn-brand btn-brand-outline px-6 py-3 text-xs uppercase tracking-wider">Back to home</a>
                <a href="{{ route('contact') }}" class="btn-brand btn-brand-solid px-6 py-3 text-xs uppercase tracking-wider">Contact us</a>
            </div>
        </div>
    </section>
@endsection
