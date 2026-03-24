<?php

namespace App\Filament\Widgets;

use App\Models\DailyStock;
use Filament\Widgets\Widget;

class TopConsumedWidget extends Widget
{
    protected static ?string $heading = 'Top Consumed Items (7 days)';

    protected static string $view = 'filament.widgets.top-consumed';

    public array $labels = [];

    public array $values = [];

    public function mount(): void
    {
        $start = now()->subDays(7)->toDateString();

        $rows = DailyStock::query()
            ->where('stock_date', '>=', $start)
            ->selectRaw('item_name, SUM(sales) as total_sales')
            ->groupBy('item_name')
            ->orderByDesc('total_sales')
            ->limit(7)
            ->get();

        $this->labels = $rows->pluck('item_name')->toArray();
        $this->values = $rows->pluck('total_sales')->map(fn($v) => (float) $v)->toArray();
    }
}
