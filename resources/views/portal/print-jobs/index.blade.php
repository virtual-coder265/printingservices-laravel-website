<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Print Jobs</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($printJobs->isEmpty())
                    <p class="text-gray-600">No print jobs yet.</p>
                @else
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-gray-500">
                                <th class="py-2 pr-4">Reference</th>
                                <th class="py-2 pr-4">Status</th>
                                <th class="py-2">Target delivery</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($printJobs as $job)
                                <tr class="border-b">
                                    <td class="py-3 pr-4"><a href="{{ route('portal.print-jobs.show', $job) }}" class="text-indigo-600 hover:underline">{{ $job->reference }}</a></td>
                                    <td class="py-3 pr-4 capitalize">{{ str_replace('_', ' ', $job->status) }}</td>
                                    <td class="py-3">{{ $job->target_delivery_date?->format('d M Y') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $printJobs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
