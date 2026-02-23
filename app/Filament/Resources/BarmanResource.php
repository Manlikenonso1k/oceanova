<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarmanResource\Pages;
use App\Models\Ingredient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BarmanResource extends Resource
{
    protected static ?string $model = Ingredient::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Bar Management';

    protected static ?string $navigationLabel = 'Bar Inventory';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('category')
                ->default('Beverage')
                ->disabled()
                ->dehydrated(),

            Forms\Components\Select::make('sub_category')
                ->required()
                ->searchable()
                ->options([
                    'Cognac' => 'Cognac',
                    'Whiskey' => 'Whiskey',
                    'Red Wine' => 'Red Wine',
                    'White Wine' => 'White Wine',
                    'Sweet Wine Red' => 'Sweet Wine Red',
                    'Sweet Wine White' => 'Sweet Wine White',
                    'Tequila' => 'Tequila',
                    'Vodka/Gin' => 'Vodka/Gin',
                    'Liqueur' => 'Liqueur',
                    'Spark/Soft' => 'Spark/Soft',
                ]),

            Forms\Components\Select::make('unit')
                ->required()
                ->options([
                    'kg' => 'kg',
                    'gram' => 'gram',
                    'pcs' => 'pcs',
                    'liter' => 'liter',
                ])
                ->default('pcs'),

            Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('₦')
                ->minValue(0),

            Forms\Components\TextInput::make('current_stock')
                ->required()
                ->numeric()
                ->minValue(0),

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

                Tables\Columns\TextColumn::make('sub_category')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('NGN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_stock_alert_level')
                    ->label('Min Alert')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('sub_category')
                    ->options([
                        'Cognac' => 'Cognac',
                        'Whiskey' => 'Whiskey',
                        'Red Wine' => 'Red Wine',
                        'White Wine' => 'White Wine',
                        'Sweet Wine Red' => 'Sweet Wine Red',
                        'Sweet Wine White' => 'Sweet Wine White',
                        'Tequila' => 'Tequila',
                        'Vodka/Gin' => 'Vodka/Gin',
                        'Liqueur' => 'Liqueur',
                        'Spark/Soft' => 'Spark/Soft',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('category', 'Beverage');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarmen::route('/'),
            'create' => Pages\CreateBarman::route('/create'),
            'edit' => Pages\EditBarman::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['barman', 'admin', 'super_admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['barman', 'admin', 'super_admin']);
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['barman', 'admin', 'super_admin']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }
}
