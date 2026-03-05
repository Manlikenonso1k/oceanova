<?php

namespace App\Filament\Widgets;

use App\Models\StockTransferLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DepartmentTransferBalanceWidget extends BaseWidget
{
    protected static ?string $heading = 'Monthly Department Transfer Balance';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['general_manager', 'admin', 'super_admin']);
    }

    public function table(Table $table): Table
    {
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        return $table
            ->query(
                StockTransferLog::query()
                    ->leftJoin('departments as from_departments', 'stock_transfer_logs.from_department_id', '=', 'from_departments.id')
                    ->leftJoin('departments as to_departments', 'stock_transfer_logs.to_department_id', '=', 'to_departments.id')
                    ->whereBetween('stock_transfer_logs.created_at', [$monthStart, $monthEnd])
                    ->selectRaw('COALESCE(to_departments.name, from_departments.name) as department_name')
                    ->selectRaw('SUM(CASE WHEN stock_transfer_logs.movement_type = "transfer_in" THEN stock_transfer_logs.quantity ELSE 0 END) as transfer_in_total')
                    ->selectRaw('SUM(CASE WHEN stock_transfer_logs.movement_type = "transfer_out" THEN stock_transfer_logs.quantity ELSE 0 END) as transfer_out_total')
                    ->groupBy('department_name')
            )
            ->columns([
                Tables\Columns\TextColumn::make('department_name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('transfer_in_total')
                    ->label('Transfer In')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 3))
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('transfer_out_total')
                    ->label('Transfer Out')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 3))
                    ->color('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('net_balance')
                    ->label('Net')
                    ->state(fn ($record): float => (float) $record->transfer_in_total - (float) $record->transfer_out_total)
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 3))
                    ->color(fn ($state): string => (float) $state >= 0 ? 'success' : 'danger'),
            ])
            ->defaultSort('department_name')
            ->paginated([5, 10, 25]);
    }
}
