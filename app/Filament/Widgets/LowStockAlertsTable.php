<?php

namespace App\Filament\Widgets;

use App\Models\Ingredient;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LowStockAlertsTable extends BaseWidget
{
    protected static ?string $heading = 'Low Stock Alerts';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['kitchen_manager', 'admin', 'super_admin']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ingredient::query()
                    ->lowStock()
                    ->orderByRaw('(min_stock_alert_level - current_stock) DESC')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ingredient')
                    ->searchable(),

                Tables\Columns\TextColumn::make('unit')
                    ->badge(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->label('Current')
                    ->numeric(decimalPlaces: 3),

                Tables\Columns\TextColumn::make('min_stock_alert_level')
                    ->label('Minimum')
                    ->numeric(decimalPlaces: 3),

                Tables\Columns\TextColumn::make('shortfall')
                    ->label('Shortfall')
                    ->state(fn (Ingredient $record): float => max(0, (float) $record->min_stock_alert_level - (float) $record->current_stock))
                    ->numeric(decimalPlaces: 3)
                    ->color('danger')
                    ->weight('bold'),
            ])
            ->paginated([5, 10, 25]);
    }
}
