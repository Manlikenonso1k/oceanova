<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SalesPieChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Top Selling Categories';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }

    protected function getData(): array
    {
        $rows = OrderItem::query()
            ->leftJoin('meals', 'order_items.meal_id', '=', 'meals.id')
            ->selectRaw('COALESCE(meals.category, "Uncategorized") as category')
            ->selectRaw('SUM(order_items.quantity) as total_qty')
            ->groupBy('category')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $rows->pluck('total_qty')->all(),
                    'backgroundColor' => [
                        '#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#8b5cf6',
                        '#14b8a6', '#f97316', '#a855f7', '#0ea5e9', '#22c55e',
                    ],
                ],
            ],
            'labels' => $rows->pluck('category')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
