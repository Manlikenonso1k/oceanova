<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Restaurant';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('customer_name')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('status')
                ->options([
                    'Pending' => 'Pending',
                    'Preparing' => 'Preparing',
                    'Delivered' => 'Delivered',
                    'Cancelled' => 'Cancelled',
                ])
                ->default('Pending')
                ->required(),

            Forms\Components\Repeater::make('items')
                ->label('Order Items')
                ->defaultItems(1)
                ->schema([
                    Forms\Components\TextInput::make('meal_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('quantity')
                        ->required()
                        ->integer()
                        ->default(1)
                        ->minValue(1),
                    Forms\Components\TextInput::make('unit_price')
                        ->numeric()
                        ->prefix('₦')
                        ->default(0)
                        ->minValue(0),
                ])
                ->columnSpanFull()
                ->required(),

            Forms\Components\TextInput::make('total')
                ->numeric()
                ->prefix('₦')
                ->disabled()
                ->dehydrated(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'Pending',
                        'info' => 'Preparing',
                        'success' => 'Delivered',
                        'danger' => 'Cancelled',
                    ]),

                Tables\Columns\TextColumn::make('total')
                    ->money('NGN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('orderItems_count')
                    ->counts('orderItems')
                    ->label('Items'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['orderItems.meal']);
    }
}
