@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :eyebrow="$page['teams']['eyebrow']"
        :title="$page['teams']['title']"
        :lead="$page['teams']['lead']"
    />

    <section class="py-24 bg-white text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (count($page['team_members'] ?? []) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach ($page['team_members'] as $member)
                        <article class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden reveal-fade-up">
                            @if (! empty($member['photo']) && media_exists($member['photo']))
                                <div class="aspect-[4/3] bg-slate-100">
                                    <img
                                        src="{{ media_url($member['photo']) }}"
                                        alt="{{ $member['name'] }}"
                                        class="h-full w-full object-cover"
                                    >
                                </div>
                            @else
                                <div class="aspect-[4/3] bg-slate-100 flex items-center justify-center">
                                    <i data-lucide="user" class="w-16 h-16 text-slate-300"></i>
                                </div>
                            @endif

                            <div class="p-6 space-y-4">
                                <div>
                                    <h2 class="text-lg font-bold font-display text-slate-900">{{ $member['name'] }}</h2>
                                    @if (! empty($member['title']))
                                        <p class="text-sm font-semibold text-gold-600 mt-1">{{ $member['title'] }}</p>
                                    @endif
                                    @if (! empty($member['department']))
                                        <p class="text-xs text-slate-500 mt-1">{{ $member['department'] }}</p>
                                    @endif
                                </div>

                                @if (! empty($member['bio']))
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $member['bio'] }}</p>
                                @endif

                                @if (! empty($member['email']) || ! empty($member['phone']))
                                    <div class="pt-2 space-y-2 border-t border-slate-100">
                                        @if (! empty($member['email']))
                                            <a href="mailto:{{ $member['email'] }}" class="flex items-center gap-2 text-sm text-slate-700 hover:text-forest-900 transition-colors">
                                                <i data-lucide="mail" class="w-4 h-4 text-gold-500 shrink-0"></i>
                                                <span class="break-all">{{ $member['email'] }}</span>
                                            </a>
                                        @endif
                                        @if (! empty($member['phone']))
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $member['phone']) }}" class="flex items-center gap-2 text-sm text-slate-700 hover:text-forest-900 transition-colors">
                                                <i data-lucide="phone" class="w-4 h-4 text-gold-500 shrink-0"></i>
                                                <span>{{ $member['phone'] }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-10 text-center reveal-fade-up">
                    <i data-lucide="users" class="w-12 h-12 text-slate-300 mx-auto"></i>
                    <h2 class="text-lg font-bold font-display text-slate-900 mt-4">Team profiles coming soon</h2>
                    <p class="text-sm text-slate-600 mt-2 max-w-xl mx-auto">
                        Our team directory is being updated. Please contact us if you need to reach a specific department.
                    </p>
                    <a href="{{ route('contact') }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-bold text-white shadow-md mt-6">
                        Contact Us &rarr;
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
