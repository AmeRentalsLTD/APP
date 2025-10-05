<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit.prevent="updateReport" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-sm rounded-xl p-6 space-y-4">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label for="period" class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Reporting period</label>
                    <select
                        wire:model="period"
                        id="period"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-primary-500"
                    >
                        @foreach ($this->periodOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="startDate" class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Start date</label>
                    <input
                        wire:model="startDate"
                        type="date"
                        id="startDate"
                        @disabled($period !== 'custom')
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-60"
                    />
                </div>

                <div>
                    <label for="endDate" class="block text-sm font-semibold text-gray-700 dark:text-gray-200">End date</label>
                    <input
                        wire:model="endDate"
                        type="date"
                        id="endDate"
                        @disabled($period !== 'custom')
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-60"
                    />
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $periodLabel }}
                </p>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    Refresh report
                </button>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total income</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-600 dark:text-emerald-400">{{ $this->formatCurrency($summary['income'] ?? 0) }}</p>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total expenses</p>
                <p class="mt-2 text-2xl font-semibold text-rose-600 dark:text-rose-400">{{ $this->formatCurrency($summary['expenses'] ?? 0) }}</p>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Net result</p>
                @php
                    $net = $summary['net'] ?? 0;
                    $netColour = $net >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400';
                @endphp
                <p class="mt-2 text-2xl font-semibold {{ $netColour }}">{{ $this->formatCurrency($net) }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Income breakdown</h3>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $this->formatCurrency($summary['income'] ?? 0) }}</span>
                </div>

                @if (! empty($categories['income']))
                    <ul class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($categories['income'] as $item)
                            <li class="flex items-center justify-between py-2 text-sm">
                                <span class="text-gray-700 dark:text-gray-200">{{ $this->formatCategory($item['category']) }}</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->formatCurrency($item['total']) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No income recorded for this period.</p>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Expense breakdown</h3>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $this->formatCurrency($summary['expenses'] ?? 0) }}</span>
                </div>

                @if (! empty($categories['expenses']))
                    <ul class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($categories['expenses'] as $item)
                            <li class="flex items-center justify-between py-2 text-sm">
                                <span class="text-gray-700 dark:text-gray-200">{{ $this->formatCategory($item['category']) }}</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->formatCurrency($item['total']) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No expenses recorded for this period.</p>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Monthly performance</h3>
                <span class="text-sm text-gray-600 dark:text-gray-300">Net movement across the selected window</span>
            </div>

            @if (! empty($trend))
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/60">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Month</th>
                                <th scope="col" class="px-4 py-2 text-right font-semibold text-gray-700 dark:text-gray-200">Income</th>
                                <th scope="col" class="px-4 py-2 text-right font-semibold text-gray-700 dark:text-gray-200">Expenses</th>
                                <th scope="col" class="px-4 py-2 text-right font-semibold text-gray-700 dark:text-gray-200">Net</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($trend as $row)
                                @php
                                    $netValue = $row['net'];
                                    $trendColour = $netValue >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400';
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ $row['month'] }}</td>
                                    <td class="px-4 py-2 text-right text-gray-900 dark:text-gray-100">{{ $this->formatCurrency($row['income']) }}</td>
                                    <td class="px-4 py-2 text-right text-gray-900 dark:text-gray-100">{{ $this->formatCurrency($row['expenses']) }}</td>
                                    <td class="px-4 py-2 text-right font-semibold {{ $trendColour }}">{{ $this->formatCurrency($netValue) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No transactions available to build a trend for this period.</p>
            @endif
        </div>
    </div>
</x-filament-panels::page>
