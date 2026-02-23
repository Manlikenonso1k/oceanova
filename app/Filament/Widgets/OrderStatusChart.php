<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class OrderStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Order Status Distribution';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }

    protected function getData(): array
    {
        $rows = Order::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $rows->pluck('total')->all(),
                    'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                ],
            ],
            'labels' => $rows->pluck('status')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
