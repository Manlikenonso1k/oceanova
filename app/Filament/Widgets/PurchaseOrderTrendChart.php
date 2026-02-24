<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithProcurementFilters;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderTrendChart extends ChartWidget
{
    use InteractsWithPageFilters;
    use InteractsWithProcurementFilters;

    protected static ?string $heading = 'Purchase Order Trend';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'procurement_officer']);
    }

    protected function getFilters(): ?array
    {
        return [
            'monthly' => 'Monthly',
            'weekly' => 'Weekly',
        ];
    }

    protected function getData(): array
    {
        $viewMode = $this->filter ?? 'monthly';

        $start = $this->getFilterStartDate()->copy()->startOfDay();
        $end = $this->getFilterEndDate()->copy()->endOfDay();
        $days = max(1, $start->diffInDays($end) + 1);
        $previousStart = $start->copy()->subDays($days);
        $previousEnd = $start->copy()->subDay()->endOfDay();

        $currentRows = $this->baseProcurementQuery()->get(['received_at']);

        $previousRows = $this->applyProcurementFilters(
            \App\Models\Procurement::query()->whereBetween('received_at', [$previousStart, $previousEnd]),
            false
        )->get(['received_at']);

        if ($viewMode === 'weekly') {
            [$labels, $currentData, $previousData] = $this->buildWeeklySeries($start, $end, $currentRows, $previousRows, $days);
        } else {
            [$labels, $currentData, $previousData] = $this->buildMonthlySeries($start, $end, $currentRows, $previousRows, $days);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Current Period',
                    'data' => $currentData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.20)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Previous Period',
                    'data' => $previousData,
                    'borderColor' => '#9ca3af',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.08)',
                    'fill' => false,
                    'tension' => 0.4,
                    'borderDash' => [6, 6],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function buildMonthlySeries($start, $end, $currentRows, $previousRows, int $days): array
    {
        $currentMap = $currentRows
            ->groupBy(fn ($row): string => $row->received_at->format('Y-m'))
            ->map(fn ($group): int => $group->count());

        $previousMap = $previousRows
            ->groupBy(fn ($row): string => $row->received_at->copy()->addDays($days)->format('Y-m'))
            ->map(fn ($group): int => $group->count());

        $labels = [];
        $currentData = [];
        $previousData = [];

        foreach (CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth()) as $date) {
            $key = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            $currentData[] = (int) ($currentMap[$key] ?? 0);
            $previousData[] = (int) ($previousMap[$key] ?? 0);
        }

        return [$labels, $currentData, $previousData];
    }

    private function buildWeeklySeries($start, $end, $currentRows, $previousRows, int $days): array
    {
        $currentMap = $currentRows
            ->groupBy(fn ($row): string => sprintf('%s-%s', $row->received_at->isoWeekYear(), $row->received_at->isoWeek()))
            ->map(fn ($group): int => $group->count());

        $previousMap = $previousRows
            ->groupBy(fn ($row): string => sprintf('%s-%s', $row->received_at->copy()->addDays($days)->isoWeekYear(), $row->received_at->copy()->addDays($days)->isoWeek()))
            ->map(fn ($group): int => $group->count());

        $labels = [];
        $currentData = [];
        $previousData = [];

        foreach (CarbonPeriod::create($start->copy()->startOfWeek(), '1 week', $end->copy()->startOfWeek()) as $week) {
            $currentKey = sprintf('%s-%s', $week->isoWeekYear(), $week->isoWeek());
            $labels[] = 'W' . $week->format('W') . ' ' . $week->format('Y');
            $currentData[] = (int) ($currentMap[$currentKey] ?? 0);
            $previousData[] = (int) ($previousMap[$currentKey] ?? 0);
        }

        return [$labels, $currentData, $previousData];
    }
}
