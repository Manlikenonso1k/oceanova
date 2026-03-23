<?php

namespace App\Filament\Widgets;

use App\Models\DailyStock;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class BarmanInsightsWidget extends ChartWidget
{
    protected static ?string $heading = 'Barman Insights: Top Consumed (7 Days)';

    public static function canView(): bool
    {
        $user = Auth::user();

        return $user
            && method_exists($user, 'hasAnyRole')
            && (bool) call_user_func([$user, 'hasAnyRole'], [
            'barman',
            'bar_manager',
            'admin',
            'super_admin',
            'general_manager',
            'manager',
        ]);
    }

    protected function getData(): array
    {
        $rows = DailyStock::query()
            ->where('category', 'Bar')
            ->whereDate('stock_date', '>=', now()->subDays(6)->toDateString())
            ->selectRaw('item_name, SUM(sales * price_ngn) as sales_value')
            ->groupBy('item_name')
            ->orderByDesc('sales_value')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Sales Value (₦)',
                    'data' => $rows->pluck('sales_value')->map(fn ($value): float => round((float) $value, 2))->all(),
                    'backgroundColor' => '#f97316',
                ],
            ],
            'labels' => $rows->pluck('item_name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
