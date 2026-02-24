<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithProcurementFilters;
use App\Models\Ingredient;
use App\Filament\Pages\SupplierProfile;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Auth;

class SpendAnalysisChart extends ChartWidget
{
    use InteractsWithPageFilters;
    use InteractsWithProcurementFilters;

    protected static ?string $heading = 'Spend Analysis';

    protected int|string|array $columnSpan = 'full';

    private array $drillDownUrls = [];

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'procurement_officer']);
    }

    protected function getFilters(): ?array
    {
        return [
            'category' => 'By Category',
            'supplier' => 'By Supplier',
        ];
    }

    protected function getData(): array
    {
        $mode = $this->filter ?? 'category';

        $query = $this->baseProcurementQuery();

        if ($mode === 'supplier') {
            $rows = $query
                ->selectRaw('supplier_name as grouping, SUM(quantity_received * unit_cost) as total')
                ->groupBy('supplier_name')
                ->orderByDesc('total')
                ->get();

            $this->drillDownUrls = $rows
                ->map(fn ($row): string => SupplierProfile::getUrl(['supplier' => $row->grouping]))
                ->all();
        } else {
            $rows = $query
                ->join('ingredients', 'ingredients.id', '=', 'procurements.ingredient_id')
                ->selectRaw('ingredients.category as grouping, SUM(procurements.quantity_received * procurements.unit_cost) as total')
                ->groupBy('ingredients.category')
                ->orderByDesc('total')
                ->get();

            $this->drillDownUrls = $rows
                ->map(function ($row): string {
                    $ingredientName = Ingredient::query()->where('category', $row->grouping)->value('name');

                    return route('filament.admin.resources.procurements.index', ['tableSearch' => $ingredientName]);
                })
                ->all();
        }

        return [
            'labels' => $rows->pluck('grouping')->all(),
            'datasets' => [
                [
                    'label' => 'Spend (₦)',
                    'data' => $rows->pluck('total')->map(fn ($value): float => (float) $value)->all(),
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 8,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array|RawJs|null
    {
        $urls = json_encode($this->drillDownUrls, JSON_THROW_ON_ERROR);

        return RawJs::make(<<<JS
        {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = Number(context.parsed.y ?? context.parsed ?? 0);
                            return 'Spend: ₦' + value.toLocaleString();
                        }
                    }
                }
            },
            onClick: (event, activeElements) => {
                if (!activeElements.length) {
                    return;
                }

                const index = activeElements[0].index;
                const urls = {$urls};
                const url = urls[index];

                if (url) {
                    window.location.href = url;
                }
            }
        }
        JS);
    }
}
