<x-filament-panels::page>
    @if ((int) ($metrics['purchase_orders'] ?? 0) === 0)
        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800">
            No procurement records found for this supplier. Check the supplier name format in procurements (spacing/casing) or create a procurement entry first.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm text-gray-500">Supplier</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $supplier }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm text-gray-500">Total Spend</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">₦{{ number_format((float) ($metrics['total_spend'] ?? 0), 2) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm text-gray-500">Purchase Orders</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ number_format((int) ($metrics['purchase_orders'] ?? 0)) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm text-gray-500">On-Time / Lead Time</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ number_format((float) ($metrics['on_time_rate'] ?? 0), 1) }}% / {{ number_format((float) ($metrics['avg_lead_time'] ?? 0), 1) }} days</p>
        </div>
    </div>
</x-filament-panels::page>
