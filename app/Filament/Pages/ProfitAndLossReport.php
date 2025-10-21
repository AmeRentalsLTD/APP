<?php

namespace App\Filament\Pages;

use App\Services\FinancialReports\ProfitAndLossReportService;
use BackedEnum;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Filament\Pages\Page;
use NumberFormatter;
use UnitEnum;

class ProfitAndLossReport extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static UnitEnum|string|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Profit & Loss';

    protected string $view = 'filament.pages.profit-and-loss-report';

    public string $period = 'this_month';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public string $periodLabel = '';

    /** @var array{income: float, expenses: float, net: float} */
    public array $summary = [
        'income' => 0.0,
        'expenses' => 0.0,
        'net' => 0.0,
    ];

    /** @var array{income: array<int, array{category: string, total: float}>, expenses: array<int, array{category: string, total: float}>} */
    public array $categories = [
        'income' => [],
        'expenses' => [],
    ];

    /** @var array<int, array{month: string, income: float, expenses: float, net: float}> */
    public array $trend = [];

    public function mount(): void
    {
        $this->syncPeriodDates();
        $this->updateReport();
    }

    public function updateReport(): void
    {
        [$start, $end] = $this->resolvePeriodDates();

        if ($this->period === 'custom' && (! $start || ! $end)) {
            $this->summary = [
                'income' => 0.0,
                'expenses' => 0.0,
                'net' => 0.0,
            ];
            $this->categories = [
                'income' => [],
                'expenses' => [],
            ];
            $this->trend = [];
            $this->periodLabel = 'Select a start and end date';

            return;
        }

        /** @var ProfitAndLossReportService $service */
        $service = app(ProfitAndLossReportService::class);

        $report = $service->generate($start, $end);

        $this->summary = $report['totals'];
        $this->categories = [
            'income' => collect($report['breakdown']['income'] ?? [])->map(function ($total, $category) {
                return [
                    'category' => (string) $category,
                    'total' => (float) $total,
                ];
            })->values()->all(),
            'expenses' => collect($report['breakdown']['expenses'] ?? [])->map(function ($total, $category) {
                return [
                    'category' => (string) $category,
                    'total' => (float) $total,
                ];
            })->values()->all(),
        ];

        $this->trend = $service->monthlyTrend($start, $end);
        $this->periodLabel = $this->describePeriod($start, $end);
    }

    public function updatedPeriod(): void
    {
        if ($this->period === 'custom') {
            $this->startDate = null;
            $this->endDate = null;
            $this->periodLabel = 'Select a start and end date';
        } else {
            $this->syncPeriodDates();
        }

        $this->updateReport();
    }

    public function updatedStartDate(): void
    {
        if ($this->period === 'custom') {
            $this->updateReport();
        }
    }

    public function updatedEndDate(): void
    {
        if ($this->period === 'custom') {
            $this->updateReport();
        }
    }

    /**
     * @return array{0: ?CarbonInterface, 1: ?CarbonInterface}
     */
    protected function resolvePeriodDates(): array
    {
        $now = Carbon::now();

        return match ($this->period) {
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'last_month' => [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ],
            'this_quarter' => [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()],
            'this_year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'last_year' => [
                $now->copy()->subYear()->startOfYear(),
                $now->copy()->subYear()->endOfYear(),
            ],
            'year_to_date' => [$now->copy()->startOfYear(), $now->copy()],
            'custom' => $this->resolveCustomDates(),
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };
    }

    /**
     * @return array{0: ?CarbonInterface, 1: ?CarbonInterface}
     */
    protected function resolveCustomDates(): array
    {
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end = $this->endDate ? Carbon::parse($this->endDate)->startOfDay() : null;

        if ($start && $end && $start->greaterThan($end)) {
            [$start, $end] = [$end, $start];
            $this->startDate = $start->toDateString();
            $this->endDate = $end->toDateString();
        }

        return [$start, $end];
    }

    protected function syncPeriodDates(): void
    {
        if ($this->period === 'custom') {
            return;
        }

        [$start, $end] = $this->resolvePeriodDates();

        $this->startDate = $start?->toDateString();
        $this->endDate = $end?->toDateString();
    }

    protected function describePeriod(?CarbonInterface $start, ?CarbonInterface $end): string
    {
        if (! $start && ! $end) {
            return 'All time';
        }

        if ($start && $end) {
            if ($start->isSameDay($end)) {
                return $start->format('j M Y');
            }

            return sprintf('%s — %s', $start->format('j M Y'), $end->format('j M Y'));
        }

        if ($start) {
            return sprintf('%s onwards', $start->format('j M Y'));
        }

        return sprintf('Until %s', $end->format('j M Y'));
    }

    public function categoryShare(array $category, float $total): float
    {
        $value = (float) ($category['total'] ?? 0.0);

        if ($total <= 0.0) {
            return 0.0;
        }

        $percentage = ($value / $total) * 100;

        return round(min(max($percentage, 0.0), 100.0), 1);
    }

    public function netMargin(): ?float
    {
        $income = (float) ($this->summary['income'] ?? 0.0);

        if ($income === 0.0) {
            return null;
        }

        $net = (float) ($this->summary['net'] ?? 0.0);

        return round(($net / $income) * 100, 1);
    }

    public function formatPercentage(?float $value): string
    {
        if ($value === null) {
            return '—';
        }

        $formatted = rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.');

        if ($formatted === '-0') {
            $formatted = '0';
        }

        return $formatted.'%';
    }

    public function periodOptions(): array
    {
        return [
            'this_month' => 'This month',
            'last_month' => 'Last month',
            'this_quarter' => 'This quarter',
            'year_to_date' => 'Year to date',
            'this_year' => 'This year',
            'last_year' => 'Last year',
            'custom' => 'Custom range',
        ];
    }

    public function formatCurrency(int|float $value): string
    {
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

        $formatted = $formatter->formatCurrency($value, 'GBP');

        if ($formatted !== false) {
            return $formatted;
        }

        return '£' . number_format($value, 2);
    }

    public function formatCategory(string $category): string
    {
        return ucwords(str_replace('_', ' ', $category));
    }
}
