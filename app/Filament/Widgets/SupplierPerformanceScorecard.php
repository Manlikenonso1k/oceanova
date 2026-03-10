<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithProcurementFilters;
use App\Filament\Pages\SupplierProfile;
use App\Models\Procurement;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SupplierPerformanceScorecard extends BaseWidget
{
    use InteractsWithPageFilters;
    use InteractsWithProcurementFilters;

    protected static ?string $heading = 'Supplier Performance';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'procurement_officer']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->buildQuery())
            ->columns([
                Tables\Columns\TextColumn::make('supplier_name')
                    ->label('Supplier')
                    ->url(fn ($record): string => SupplierProfile::getUrl(['supplier' => $record->supplier_name]))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('on_time_rate')
                    ->label('On-Time Delivery (%)')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 1) . '%')
                    ->color(fn ($state): string => (float) $state < 80 ? 'danger' : 'success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('avg_lead_time')
                    ->label('Avg Lead Time (days)')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 1))
                    ->sortable(),

                Tables\Columns\TextColumn::make('quality_rating')
                    ->label('Quality Rating')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 1) . '/100')
                    ->color(fn ($state): string => (float) $state < 80 ? 'danger' : 'success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_spend')
                    ->label('Total Spend')
                    ->money('NGN')
                    ->sortable(),
            ])
            ->defaultSort('quality_rating', 'desc')
            ->paginated([5, 10, 25]);
    }

    private function buildQuery(): Builder
    {
        $query = Procurement::query()
            ->whereNotNull('supplier_name')
            ->where('supplier_name', '!=', '')
            ->selectRaw('MIN(id) as id')
            ->selectRaw('supplier_name')
            ->selectRaw('SUM(CASE WHEN unit_cost > 0 THEN unit_cost WHEN unit_price > 0 AND quantity_received > 0 THEN unit_price * quantity_received ELSE 0 END) as total_spend')
            ->selectRaw('AVG(CASE WHEN status = "completed" THEN 100 ELSE 0 END) as on_time_rate')
            ->selectRaw('AVG(TIMESTAMPDIFF(DAY, created_at, received_at)) as avg_lead_time')
            ->selectRaw('GREATEST(0, LEAST(100, (AVG(CASE WHEN status = "completed" THEN 100 ELSE 0 END) * 0.7) + ((30 - AVG(TIMESTAMPDIFF(DAY, created_at, received_at))) * 1.0))) as quality_rating')
            ->groupBy('supplier_name')
            ->orderByDesc('quality_rating');

        return $this->applyProcurementFilters($query);
    }
}
