<x-filament::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($buckets as $label => $data)
            <div class="rounded-lg bg-white p-4 shadow">
                <div class="text-sm font-semibold text-gray-600">{{ $label }} days</div>
                <div class="mt-2 text-2xl font-bold">£{{ number_format($data['total'], 2) }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 space-y-6">
        @foreach($buckets as $label => $data)
            <div>
                <h3 class="text-lg font-semibold text-gray-700">{{ $label }} days</h3>
                <table class="mt-2 min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Invoice</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Customer</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Due date</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($data['invoices'] as $invoice)
                            <tr>
                                <td class="px-4 py-2">{{ $invoice['number'] }}</td>
                                <td class="px-4 py-2">{{ $invoice['customer'] }}</td>
                                <td class="px-4 py-2">{{ $invoice['due_date'] }}</td>
                                <td class="px-4 py-2 text-right">£{{ number_format($invoice['balance'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">No outstanding invoices.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</x-filament::page>
