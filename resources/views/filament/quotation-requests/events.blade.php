@php
    /** @var \App\Models\QuotationRequest $record */
    $record = $getRecord();
    $events = $record->events()->with('user')->latest('created_at')->get();
@endphp

@if ($events->isEmpty())
    <p class="text-sm text-gray-500">No events logged yet.</p>
@else
    <ul class="space-y-2 text-sm">
        @foreach ($events as $event)
            <li class="flex flex-wrap gap-2">
                <span class="font-medium">{{ str_replace('_', ' ', $event->event) }}</span>
                <span class="text-gray-500">{{ $event->created_at?->format('Y-m-d H:i') }}</span>
                @if ($event->user)
                    <span class="text-gray-500">— {{ $event->user->name }}</span>
                @endif
            </li>
        @endforeach
    </ul>
@endif
