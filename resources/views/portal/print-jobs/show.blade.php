<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Print Job {{ $printJob->reference }}</h2>
            <a href="{{ route('portal.print-jobs') }}" class="text-sm text-indigo-600 hover:underline">Back to list</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Status</dt><dd class="font-medium capitalize">{{ str_replace('_', ' ', $printJob->status) }}</dd></div>
                    <div><dt class="text-gray-500">Target delivery</dt><dd class="font-medium">{{ $printJob->target_delivery_date?->format('d M Y') ?? '—' }}</dd></div>
                    @if ($printJob->quotation)
                        <div><dt class="text-gray-500">Quotation</dt><dd class="font-medium">{{ $printJob->quotation->reference }}</dd></div>
                    @endif
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Status history</h3>
                <ul class="space-y-2 text-sm">
                    @forelse ($printJob->statusLogs as $log)
                        <li class="border-b pb-2">
                            <span class="font-medium capitalize">{{ str_replace('_', ' ', $log->status) }}</span>
                            <span class="text-gray-500"> — {{ $log->created_at->format('d M Y H:i') }}</span>
                            @if ($log->notes)<p class="text-gray-600 mt-1">{{ $log->notes }}</p>@endif
                        </li>
                    @empty
                        <li class="text-gray-600">No status updates yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
