<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Client Portal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('portal.quotations') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:ring-2 hover:ring-indigo-500 transition">
                    <h3 class="font-semibold text-gray-900">My Quotations</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ $quotations->count() }} recent requests</p>
                </a>
                <a href="{{ route('portal.orders') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:ring-2 hover:ring-indigo-500 transition">
                    <h3 class="font-semibold text-gray-900">My Orders</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ $orders->count() }} recent orders</p>
                </a>
                <a href="{{ route('portal.print-jobs') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:ring-2 hover:ring-indigo-500 transition">
                    <h3 class="font-semibold text-gray-900">Print Jobs</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ $printJobs->count() }} active jobs</p>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Welcome, {{ $user->name }}. Use the portal to track quotations, orders, and production jobs.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
