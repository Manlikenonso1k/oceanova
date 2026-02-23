<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryLogResource\Pages;
use App\Models\InventoryLog;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InventoryLogResource extends Resource
{
    protected static ?string $model = InventoryLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Ingredient')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'in',
                        'warning' => 'out',
                        'danger' => 'waste',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Actor')
                    ->searchable()
                    ->placeholder('System'),

                Tables\Columns\TextColumn::make('reason')
                    ->limit(60)
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Logged At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'in' => 'Stock In',
                        'out' => 'Stock Out',
                        'waste' => 'Waste',
                    ]),

                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload(),

                Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('from_date'),
                        Forms\Components\DatePicker::make('until_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from_date'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until_date'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($livewire) {
                        $query = method_exists($livewire, 'getFilteredTableQuery')
                            ? $livewire->getFilteredTableQuery()
                            : InventoryLog::query();

                        $rows = $query
                            ->with(['ingredient:id,name', 'user:id,email'])
                            ->orderByDesc('created_at')
                            ->get();

                        $filename = 'inventory-logs-' . now()->format('Y-m-d-His') . '.csv';

                        return response()->streamDownload(function () use ($rows): void {
                            $stream = fopen('php://output', 'w');

                            fputcsv($stream, ['ingredient', 'type', 'quantity', 'user', 'reason', 'logged_at']);

                            foreach ($rows as $row) {
                                fputcsv($stream, [
                                    $row->ingredient?->name,
                                    $row->type,
                                    $row->quantity,
                                    $row->user?->email ?? 'system',
                                    $row->reason,
                                    optional($row->created_at)->toDateTimeString(),
                                ]);
                            }

                            fclose($stream);
                        }, $filename, [
                            'Content-Type' => 'text/csv',
                        ]);
                    }),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryLogs::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['kitchen_manager', 'admin', 'super_admin']);
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
