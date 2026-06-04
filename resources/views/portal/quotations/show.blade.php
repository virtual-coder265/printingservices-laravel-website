<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quotation {{ $quotation->reference }}</h2>
            <a href="{{ route('portal.quotations') }}" class="text-sm text-indigo-600 hover:underline">Back to list</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Status</dt><dd class="font-medium capitalize">{{ str_replace('_', ' ', $quotation->status) }}</dd></div>
                    <div><dt class="text-gray-500">Total</dt><dd class="font-medium">MWK {{ number_format($quotation->total_amount, 2) }}</dd></div>
                    <div class="md:col-span-2"><dt class="text-gray-500">Notes</dt><dd class="font-medium">{{ $quotation->notes }}</dd></div>
                    @if ($quotation->rejection_reason)
                        <div class="md:col-span-2"><dt class="text-gray-500">Rejection reason</dt><dd class="font-medium text-red-700">{{ $quotation->rejection_reason }}</dd></div>
                    @endif
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Line items</h3>
                <ul class="space-y-2 text-sm">
                    @foreach ($quotation->items as $item)
                        <li class="flex justify-between border-b pb-2">
                            <span>{{ $item->description }} &times; {{ $item->quantity }}</span>
                            <span>MWK {{ number_format($item->line_total, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if (in_array($quotation->status, ['pending_review', 'reviewing', 'approved']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold mb-4">Upload artwork</h3>
                    <form action="{{ route('portal.quotations.artwork', $quotation) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="file" name="artwork" required class="block w-full text-sm">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Upload</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
