<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class MealPopularityChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Most Ordered Meals';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }

    protected function getData(): array
    {
        $rows = OrderItem::query()
            ->with('meal')
            ->selectRaw('meal_name, SUM(quantity) as qty')
            ->groupBy('meal_name')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $rows->pluck('qty')->all(),
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $rows->pluck('meal_name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
