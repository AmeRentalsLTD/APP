<x-filament::page>
    <form wire:submit.prevent="runReport" class="space-y-4">
        {{ $this->form }}
        <x-filament::button type="submit">Run report</x-filament::button>
    </form>

    <div class="mt-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Income</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Expenses</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($results as $row)
                    <tr>
                        <td class="px-4 py-2">{{ $row['vehicle'] }}</td>
                        <td class="px-4 py-2 text-right">£{{ number_format($row['income'], 2) }}</td>
                        <td class="px-4 py-2 text-right">£{{ number_format($row['expenses'], 2) }}</td>
                        <td class="px-4 py-2 text-right font-semibold">£{{ number_format($row['profit'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::page>
