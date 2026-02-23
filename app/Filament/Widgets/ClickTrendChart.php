<?php

namespace App\Filament\Widgets;

use App\Models\Click;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ClickTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Click Activity (14 days)';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }

    protected function getData(): array
    {
        $start = now()->subDays(13)->startOfDay();
        $end = now()->endOfDay();

        $rows = Click::query()
            ->selectRaw('DATE(clicked_at) as day, COUNT(*) as total')
            ->whereBetween('clicked_at', [$start, $end])
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $data = [];

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('M d');
            $data[] = (int) ($rows[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Clicks',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
