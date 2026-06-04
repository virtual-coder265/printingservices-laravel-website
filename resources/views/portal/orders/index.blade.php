<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Orders</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($orders->isEmpty())
                    <p class="text-gray-600">No orders yet. <a href="{{ route('products') }}" class="text-indigo-600 hover:underline">Browse products</a></p>
                @else
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
                            @foreach ($orders as $order)
                                <tr class="border-b">
                                    <td class="py-3 pr-4"><a href="{{ route('portal.orders.show', $order) }}" class="text-indigo-600 hover:underline">{{ $order->reference }}</a></td>
                                    <td class="py-3 pr-4 capitalize">{{ $order->status }}</td>
                                    <td class="py-3 pr-4">MWK {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="py-3">{{ $order->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
