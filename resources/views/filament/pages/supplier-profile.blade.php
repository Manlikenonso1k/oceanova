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

    <div class="mt-6 rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
        <div class="mb-3 flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-900">Procurement Entries</h3>
            <p class="text-sm text-gray-500">{{ number_format(count($entries)) }} row(s)</p>
        </div>

        @if (count($entries) === 0)
            <p class="text-sm text-gray-500">No entry rows found for this supplier.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-600">Date</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-600">Ingredient</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-600">Qty</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-600">Amount (Total)</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-600">Line Total</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($entries as $entry)
                            <tr>
                                <td class="px-3 py-2 text-gray-700">{{ $entry['received_at'] }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $entry['ingredient'] }}</td>
                                <td class="px-3 py-2 text-right text-gray-700">{{ number_format((float) $entry['quantity_received'], 3) }}</td>
                                <td class="px-3 py-2 text-right text-gray-700">₦{{ number_format((float) $entry['unit_cost'], 2) }}</td>
                                <td class="px-3 py-2 text-right font-semibold text-gray-900">₦{{ number_format((float) $entry['line_total'], 2) }}</td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $entry['status'] === 'completed' ? 'bg-green-100 text-green-700' : ($entry['status'] === 'approved' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ ucfirst($entry['status']) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-filament-panels::page>
