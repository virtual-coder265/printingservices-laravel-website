<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order {{ $order->reference }}</h2>
            <a href="{{ route('portal.orders') }}" class="text-sm text-indigo-600 hover:underline">Back to list</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Status</dt><dd class="font-medium capitalize">{{ $order->status }}</dd></div>
                    <div><dt class="text-gray-500">Payment</dt><dd class="font-medium capitalize">{{ $order->payment_status }}</dd></div>
                    <div><dt class="text-gray-500">Total</dt><dd class="font-medium">MWK {{ number_format($order->total_amount, 2) }}</dd></div>
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Items</h3>
                <ul class="space-y-2 text-sm">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between border-b pb-2">
                            <span>{{ $item->description }} &times; {{ $item->quantity }}</span>
                            <span>MWK {{ number_format($item->line_total, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
