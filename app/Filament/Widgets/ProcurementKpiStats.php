<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithProcurementFilters;
use App\Models\Procurement;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ProcurementKpiStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;
    use InteractsWithProcurementFilters;

    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 4;
    }

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'procurement_officer']);
    }

    protected function getStats(): array
    {
        $currentStart = $this->getFilterStartDate();
        $currentEnd = $this->getFilterEndDate();
        $days = max(1, $currentStart->diffInDays($currentEnd) + 1);

        $previousStart = $currentStart->copy()->subDays($days);
        $previousEnd = $currentStart->copy()->subDay();

        $currentQuery = $this->baseProcurementQuery();

        $currentSpend = (float) (clone $currentQuery)
            ->selectRaw('COALESCE(SUM(quantity_received * unit_cost), 0) as total_spend')
            ->value('total_spend');

        $previousSpend = (float) Procurement::query()
            ->whereBetween('received_at', [$previousStart->startOfDay(), $previousEnd->endOfDay()])
            ->selectRaw('COALESCE(SUM(quantity_received * unit_cost), 0) as total_spend')
            ->value('total_spend');

        $currentOrders = (int) (clone $currentQuery)->count();
        $previousOrders = (int) Procurement::query()
            ->whereBetween('received_at', [$previousStart->startOfDay(), $previousEnd->endOfDay()])
            ->count();

        $activeSuppliers = (int) (clone $currentQuery)->distinct('supplier_name')->count('supplier_name');
        $previousActiveSuppliers = (int) Procurement::query()
            ->whereBetween('received_at', [$previousStart->startOfDay(), $previousEnd->endOfDay()])
            ->distinct('supplier_name')
            ->count('supplier_name');

        $costSavings = max(0, $previousSpend - $currentSpend);
        $costSavingsPrevious = max(0, $previousSpend - ((float) Procurement::query()
            ->whereBetween('received_at', [$previousStart->copy()->subDays($days)->startOfDay(), $previousStart->copy()->subDay()->endOfDay()])
            ->selectRaw('COALESCE(SUM(quantity_received * unit_cost), 0) as total_spend')
            ->value('total_spend')));

        return [
            $this->buildStat(
                'Total Procurement Spend',
                '₦' . number_format($currentSpend, 2),
                $this->percentageChange($currentSpend, $previousSpend),
                'heroicon-o-banknotes'
            ),
            $this->buildStat(
                'Total Purchase Orders',
                number_format($currentOrders),
                $this->percentageChange($currentOrders, $previousOrders),
                'heroicon-o-clipboard-document-list'
            ),
            $this->buildStat(
                'Active Suppliers',
                number_format($activeSuppliers),
                $this->percentageChange($activeSuppliers, $previousActiveSuppliers),
                'heroicon-o-building-storefront'
            ),
            $this->buildStat(
                'Cost Savings',
                '₦' . number_format($costSavings, 2),
                $this->percentageChange($costSavings, $costSavingsPrevious),
                'heroicon-o-arrow-trending-down',
                inverseGood: true
            ),
        ];
    }

    private function buildStat(string $label, string $value, float $change, string $icon, bool $inverseGood = false): Stat
    {
        $isPositive = $change >= 0;
        $good = $inverseGood ? !$isPositive : $isPositive;

        return Stat::make($label, $value)
            ->description(($isPositive ? '+' : '') . number_format($change, 1) . '% vs previous period')
            ->descriptionIcon($isPositive ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($good ? 'success' : 'danger')
            ->icon($icon);
    }

    private function percentageChange(float|int $current, float|int $previous): float
    {
        if ((float) $previous === 0.0) {
            return (float) $current === 0.0 ? 0.0 : 100.0;
        }

        return (((float) $current - (float) $previous) / (float) $previous) * 100;
    }
}
