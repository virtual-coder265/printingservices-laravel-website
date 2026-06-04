@php
    /** @var \App\Models\QuotationRequest $record */
    $record = $getRecord();
    $attachments = $record->attachments;
@endphp

@if ($attachments->isEmpty())
    <p class="text-sm text-gray-500">No files uploaded.</p>
@else
    <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
        @foreach ($attachments as $attachment)
            <li class="flex items-center justify-between gap-4 px-4 py-3 text-sm">
                <span>{{ $attachment->original_name }} ({{ number_format($attachment->size_bytes / 1024, 1) }} KB)</span>
                <a
                    href="{{ route('admin.quotation-requests.attachments.download', $attachment) }}"
                    class="text-primary-600 hover:underline font-medium"
                    target="_blank"
                    rel="noopener"
                >
                    Download
                </a>
            </li>
        @endforeach
    </ul>
@endif

@if ($record->payload('attachments_reference_url'))
    <p class="mt-3 text-sm">
        <span class="font-medium">External link:</span>
        <a href="{{ $record->payload('attachments_reference_url') }}" class="text-primary-600 hover:underline break-all" target="_blank" rel="noopener">
            {{ $record->payload('attachments_reference_url') }}
        </a>
    </p>
@endif
