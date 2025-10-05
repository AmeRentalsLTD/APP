<x-filament-panels::page>
    <div class="space-y-10">
        @php
            $incomeTotal = $summary['income'] ?? 0;
            $expenseTotal = $summary['expenses'] ?? 0;
            $netTotal = $summary['net'] ?? 0;
            $isNetPositive = $netTotal >= 0;
            $netLabel = $isNetPositive ? 'Profitable period' : 'Operating loss';
            $netBadgeClasses = $isNetPositive
                ? 'bg-emerald-400/20 text-emerald-50 ring-1 ring-inset ring-emerald-300/40 dark:bg-emerald-500/15 dark:text-emerald-200'
                : 'bg-rose-400/20 text-rose-50 ring-1 ring-inset ring-rose-300/40 dark:bg-rose-500/15 dark:text-rose-200';
            $netMargin = $this->netMargin();
            $topIncome = collect($categories['income'] ?? [])->sortByDesc('total')->first();
            $topExpense = collect($categories['expenses'] ?? [])->sortByDesc('total')->first();
        @endphp

        <section class="relative overflow-hidden rounded-3xl border border-primary-200/60 bg-gradient-to-br from-primary-600 via-primary-500 to-primary-700 text-white shadow-xl">
            <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>

            <div class="relative px-6 py-10 sm:px-10">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-4">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h12a2.25 2.25 0 0 0 2.25-2.25V3M3.75 3h16.5M3.75 3 6 6m12-3 2.25 3M6 20.25h12" />
                            </svg>
                            Profit &amp; Loss overview
                        </span>
                        <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">Financial health at a glance</h1>
                        <p class="text-sm text-white/80 sm:text-base">Detailed performance for <span class="font-semibold text-white">{{ $periodLabel }}</span>. Monitor profitability, understand category performance and keep an eye on monthly momentum.</p>
                    </div>

                    <div class="w-full max-w-sm rounded-2xl bg-white/10 p-6 backdrop-blur">
                        <div class="flex items-center justify-between text-sm font-medium text-white/80">
                            <span>Net result</span>
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[0.7rem] font-semibold {{ $netBadgeClasses }}">
                                <span class="inline-flex h-2.5 w-2.5 items-center justify-center">
                                    <span class="h-2 w-2 rounded-full {{ $isNetPositive ? 'bg-emerald-300' : 'bg-rose-300' }}"></span>
                                </span>
                                {{ $netLabel }}
                            </span>
                        </div>
                        <p class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">{{ $this->formatCurrency($netTotal) }}</p>

                        <dl class="mt-6 grid grid-cols-2 gap-4 text-sm text-white/80">
                            <div class="space-y-1">
                                <dt class="font-medium text-white">Income</dt>
                                <dd class="text-lg font-semibold text-white/95">{{ $this->formatCurrency($incomeTotal) }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="font-medium text-white">Expenses</dt>
                                <dd class="text-lg font-semibold text-white/95">{{ $this->formatCurrency($expenseTotal) }}</dd>
                            </div>
                            <div class="col-span-2 space-y-1 border-t border-white/10 pt-4">
                                <dt class="font-medium text-white">Net margin</dt>
                                <dd class="text-base font-semibold text-white/95">{{ $this->formatPercentage($netMargin) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </section>

        <form wire:submit.prevent="updateReport" class="rounded-2xl border border-gray-200 bg-white/70 p-6 shadow-sm backdrop-blur dark:border-gray-700 dark:bg-gray-900/80">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="grid flex-1 gap-4 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <label for="period" class="block text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200">Reporting period</label>
                        <select
                            wire:model="period"
                            id="period"
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-white/70 px-3 py-2 text-sm font-medium text-gray-900 shadow-inner focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            @foreach ($this->periodOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="startDate" class="block text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200">Start date</label>
                        <input
                            wire:model="startDate"
                            type="date"
                            id="startDate"
                            @disabled($period !== 'custom')
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-white/70 px-3 py-2 text-sm font-medium text-gray-900 shadow-inner focus:border-primary-500 focus:ring-primary-500 disabled:cursor-not-allowed disabled:opacity-60 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        />
                    </div>

                    <div>
                        <label for="endDate" class="block text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200">End date</label>
                        <input
                            wire:model="endDate"
                            type="date"
                            id="endDate"
                            @disabled($period !== 'custom')
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-white/70 px-3 py-2 text-sm font-medium text-gray-900 shadow-inner focus:border-primary-500 focus:ring-primary-500 disabled:cursor-not-allowed disabled:opacity-60 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        />
                    </div>
                </div>

                <div class="flex flex-col items-start gap-3 lg:items-end">
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $periodLabel }}</p>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-600/30 transition hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                    >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992m-18.03 0h2.987m3.761 0a3.75 3.75 0 0 1 7.125 0m-7.125 0a3.75 3.75 0 0 0 7.125 0m0 0H21M3 15.75h5.25m4.5 0H21M3 18.75h3.75m4.5 0H21" />
                        </svg>
                        Refresh report
                    </button>
                </div>
            </div>
        </form>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total income</p>
                        <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $this->formatCurrency($incomeTotal) }}</p>
                    </div>
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">Includes all recognised income transactions within the selected window.</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total expenses</p>
                        <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $this->formatCurrency($expenseTotal) }}</p>
                    </div>
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" transform="rotate(45 12 12)" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">Operational expenditure, payroll and other outgoing totals for your period.</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                @php
                    $netColour = $isNetPositive ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400';
                @endphp
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Net result</p>
                        <p class="mt-3 text-3xl font-semibold {{ $netColour }}">{{ $this->formatCurrency($netTotal) }}</p>
                    </div>
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full {{ $isNetPositive ? 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300' }}">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v5.25M3 8.25a45.11 45.11 0 0 0 7.5 3.094M21 3v5.25M21 8.25a45.11 45.11 0 0 1-7.5 3.094M3 3a48.108 48.108 0 0 1 9 2.25A48.108 48.108 0 0 0 21 3M3 21v-5.25M3 15.75a45.11 45.11 0 0 1 7.5-3.094M21 21v-5.25M21 15.75a45.11 45.11 0 0 0-7.5-3.094M3 21a48.108 48.108 0 0 0 9-2.25A48.108 48.108 0 0 1 21 21" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">{{ $isNetPositive ? 'Positive cash flow generated for the chosen period.' : 'Net loss recorded — review expenses and identify savings opportunities.' }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Income breakdown</h3>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $this->formatCurrency($incomeTotal) }}</span>
                    </div>

                    @if (! empty($categories['income']))
                        <ul class="mt-5 space-y-4">
                            @foreach ($categories['income'] as $item)
                                @php
                                    $share = $this->categoryShare($item, $incomeTotal);
                                @endphp
                                <li class="rounded-xl border border-gray-100 bg-gray-50/70 p-4 shadow-sm transition hover:border-primary-200 hover:bg-primary-50/60 dark:border-gray-700 dark:bg-gray-800">
                                    <div class="flex items-center justify-between text-sm font-medium">
                                        <span class="text-gray-700 dark:text-gray-200">{{ $this->formatCategory($item['category']) }}</span>
                                        <span class="text-gray-900 dark:text-gray-100">{{ $this->formatCurrency($item['total']) }}</span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>Share of total</span>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $this->formatPercentage($share) }}</span>
                                    </div>
                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-white/60 dark:bg-gray-700">
                                        <div class="h-full rounded-full bg-emerald-500/80" style="width: {{ $share }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No income recorded for this period.</p>
                    @endif
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Expense breakdown</h3>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $this->formatCurrency($expenseTotal) }}</span>
                    </div>

                    @if (! empty($categories['expenses']))
                        <ul class="mt-5 space-y-4">
                            @foreach ($categories['expenses'] as $item)
                                @php
                                    $share = $this->categoryShare($item, $expenseTotal);
                                @endphp
                                <li class="rounded-xl border border-gray-100 bg-gray-50/70 p-4 shadow-sm transition hover:border-rose-200 hover:bg-rose-50/60 dark:border-gray-700 dark:bg-gray-800">
                                    <div class="flex items-center justify-between text-sm font-medium">
                                        <span class="text-gray-700 dark:text-gray-200">{{ $this->formatCategory($item['category']) }}</span>
                                        <span class="text-gray-900 dark:text-gray-100">{{ $this->formatCurrency($item['total']) }}</span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>Share of total</span>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $this->formatPercentage($share) }}</span>
                                    </div>
                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-white/60 dark:bg-gray-700">
                                        <div class="h-full rounded-full bg-rose-500/80" style="width: {{ $share }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No expenses recorded for this period.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Monthly performance</h3>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Net movement overview</span>
                    </div>

                    @if (! empty($trend))
                        <div class="mt-5 max-h-96 overflow-y-auto pr-2">
                            <ul class="space-y-3 text-sm">
                                @foreach ($trend as $row)
                                    @php
                                        $netValue = $row['net'];
                                        $trendColour = $netValue >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400';
                                    @endphp
                                    <li class="rounded-xl border border-gray-100 bg-gray-50/70 p-4 dark:border-gray-700 dark:bg-gray-800">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $row['month'] }}</p>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $this->formatCurrency($row['income']) }} income · {{ $this->formatCurrency($row['expenses']) }} expenses</p>
                                            </div>
                                            <span class="text-sm font-semibold {{ $trendColour }}">{{ $this->formatCurrency($netValue) }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="mt-5 text-sm text-gray-500 dark:text-gray-400">No transactions available to build a trend for this period.</p>
                    @endif
                </div>

                <div class="rounded-2xl border border-dashed border-primary-200 bg-primary-50/60 p-6 text-primary-900 shadow-sm dark:border-primary-500/60 dark:bg-primary-500/10 dark:text-primary-200">
                    <h3 class="text-lg font-semibold">Key insights</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-primary-500/20 text-primary-700 dark:bg-primary-500/30 dark:text-primary-100">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </span>
                            <span>
                                {{ $isNetPositive ? 'Your income comfortably outpaces expenses.' : 'Expenses currently exceed income. Consider tightening spend or improving revenue streams.' }}
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-primary-500/20 text-primary-700 dark:bg-primary-500/30 dark:text-primary-100">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12 6 9.75l3.75 2.25L13.5 9l4.5 3m-15 4.5 3.75-2.25L10.5 15l3.75-2.25L18 15m-15-9 3.75 2.25L10.5 6l3.75 2.25L18 6" />
                                </svg>
                            </span>
                            <span>
                                @if ($topIncome)
                                    {{ $this->formatCategory($topIncome['category']) }} is your top income stream at {{ $this->formatPercentage($this->categoryShare($topIncome, $incomeTotal)) }} of revenue.
                                @else
                                    No income categories available yet — add transactions to see insight here.
                                @endif
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-primary-500/20 text-primary-700 dark:bg-primary-500/30 dark:text-primary-100">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                            </span>
                            <span>
                                @if ($topExpense)
                                    Largest expense category is {{ $this->formatCategory($topExpense['category']) }}, representing {{ $this->formatPercentage($this->categoryShare($topExpense, $expenseTotal)) }} of spend.
                                @else
                                    No expenses recorded yet — keep an eye on spending as data arrives.
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
