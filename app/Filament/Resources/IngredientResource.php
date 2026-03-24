<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngredientResource\Pages;
use App\Models\Ingredient;
use App\Services\InventoryService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('unit')
                ->required()
                ->maxLength(50),

            Forms\Components\TextInput::make('weight')
                ->required()
                ->numeric()
                ->minValue(0),

            Forms\Components\TextInput::make('current_stock')
                ->required()
                ->numeric()
                ->minValue(0),

            Forms\Components\TextInput::make('price')
                ->label('Price (₦)')
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->disabled(fn (): bool => !(Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager']))),

            Forms\Components\FileUpload::make('image')
                ->image()
                ->directory('ingredients')
                ->preserveFilenames()
                ->visibility('public')
                ->disabled(fn (): bool => !(Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager']))),

            Forms\Components\TextInput::make('min_stock_alert_level')
                ->required()
                ->numeric()
                ->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit')
                    ->sortable(),

                Tables\Columns\TextColumn::make('weight')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->numeric(decimalPlaces: 3)
                    ->weight('bold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_stock_alert_level')
                    ->label('Min Alert')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),

                Tables\Columns\IconColumn::make('low_stock')
                    ->label('Low Stock')
                    ->boolean()
                    ->state(fn (Ingredient $record): bool => (float) $record->current_stock <= (float) $record->min_stock_alert_level),
            ])
            ->filters([
                Filter::make('low_stock')
                    ->label('Low Stock Alerts')
                    ->query(fn (Builder $query): Builder => $query->lowStock()),
            ])
            ->actions([
                Tables\Actions\Action::make('log_waste')
                    ->label('Log Waste')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (): bool => Auth::check() && Auth::user()->hasAnyRole(['kitchen_manager', 'admin', 'super_admin']))
                    ->form([
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(0.001),
                        Forms\Components\Textarea::make('reason')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (Ingredient $record, array $data): void {
                        try {
                            app(InventoryService::class)->logWaste(
                                $record->id,
                                (float) $data['quantity'],
                                (string) $data['reason'],
                                Auth::id(),
                            );

                            Notification::make()
                                ->title('Waste logged successfully')
                                ->success()
                                ->send();
                        } catch (RuntimeException $exception) {
                            Notification::make()
                                ->title($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn (): bool => Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin'])),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngredients::route('/'),
            'create' => Pages\CreateIngredient::route('/create'),
            'edit' => Pages\EditIngredient::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager']);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }
}
