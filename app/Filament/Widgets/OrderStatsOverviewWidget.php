<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderStatsOverviewWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'kitchen_manager', 'kitchen']);
    }

    protected function getStats(): array
    {
        $today = Carbon::today();

        $salesToday = (float) Order::query()
            ->whereDate('created_at', $today)
            ->whereIn('status', ['ready', 'served'])
            ->sum('total_price');

        $pendingOrders = (int) Order::query()
            ->whereIn('status', ['pending', 'cooking'])
            ->count();

        $avgPrep = (float) Order::query()
            ->whereIn('status', ['ready', 'served'])
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes')
            ->value('avg_minutes');

        return [
            Stat::make('Total Sales Today', '₦' . number_format($salesToday, 2)),
            Stat::make('Pending Orders', number_format($pendingOrders)),
            Stat::make('Average Prep Time', number_format($avgPrep, 1) . ' mins'),
        ];
    }
}
