<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockTransferLogResource\Pages;
use App\Models\StockTransferLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StockTransferLogResource extends Resource
{
    protected static ?string $model = StockTransferLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    protected static ?string $navigationLabel = 'Transfer Logs';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingredient.name')->label('Item')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('movement_type')->badge()->label('Movement')->sortable(),
                Tables\Columns\TextColumn::make('fromDepartment.name')->label('From')->sortable(),
                Tables\Columns\TextColumn::make('toDepartment.name')->label('To')->sortable(),
                Tables\Columns\TextColumn::make('quantity')->numeric(decimalPlaces: 3)->sortable(),
                Tables\Columns\TextColumn::make('actor.name')->label('Processed By')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('movement_type')
                    ->options([
                        'transfer_out' => 'Transfer Out',
                        'transfer_in' => 'Transfer In',
                    ]),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockTransferLogs::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager', 'store_keeper']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
