<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Quotations</h2>
            <a href="{{ route('quotation') }}" class="text-sm text-indigo-600 hover:underline">New request</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($quotations->isEmpty())
                        <p class="text-gray-600">No quotation requests yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-gray-500">
                                        <th class="py-2 pr-4">Reference</th>
                                        <th class="py-2 pr-4">Status</th>
                                        <th class="py-2 pr-4">Total</th>
                                        <th class="py-2">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotations as $quotation)
                                        <tr class="border-b">
                                            <td class="py-3 pr-4"><a href="{{ route('portal.quotations.show', $quotation) }}" class="text-indigo-600 hover:underline">{{ $quotation->reference }}</a></td>
                                            <td class="py-3 pr-4 capitalize">{{ str_replace('_', ' ', $quotation->status) }}</td>
                                            <td class="py-3 pr-4">MWK {{ number_format($quotation->total_amount, 2) }}</td>
                                            <td class="py-3">{{ $quotation->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $quotations->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
