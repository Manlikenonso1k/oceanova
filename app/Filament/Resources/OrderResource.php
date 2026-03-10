<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Meal;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Restaurant';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('table_number')
                ->label('Table Number')
                ->required()
                ->maxLength(255),

            Forms\Components\Hidden::make('waiter_id')
                ->default(fn (): ?int => Auth::id()),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'cooking' => 'Cooking',
                    'ready' => 'Ready',
                    'served' => 'Served',
                ])
                ->default('pending')
                ->required(),

            Forms\Components\Repeater::make('items')
                ->label('Order Items')
                ->defaultItems(1)
                ->live()
                ->schema([
                    Forms\Components\Select::make('meal_id')
                        ->label('Meal')
                        ->options(fn (): array => Meal::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get): void {
                            $meal = Meal::query()->find($state);

                            if ($meal) {
                                $set('meal_name', (string) $meal->name);
                                $set('unit_price', (float) $meal->price);
                            }

                            $qty = max(1, (int) ($get('quantity') ?? 1));
                            $unitPrice = (float) ($get('unit_price') ?? 0);
                            $set('subtotal', $qty * $unitPrice);
                        }),
                    Forms\Components\Hidden::make('meal_name'),
                    Forms\Components\TextInput::make('quantity')
                        ->required()
                        ->integer()
                        ->default(1)
                        ->minValue(1)
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get): void {
                            $qty = max(1, (int) ($state ?? 1));
                            $unitPrice = (float) ($get('unit_price') ?? 0);
                            $set('subtotal', $qty * $unitPrice);
                        }),
                    Forms\Components\TextInput::make('unit_price')
                        ->numeric()
                        ->prefix('₦')
                        ->default(0)
                        ->minValue(0)
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get): void {
                            $qty = max(1, (int) ($get('quantity') ?? 1));
                            $unitPrice = max(0, (float) ($state ?? 0));
                            $set('subtotal', $qty * $unitPrice);
                        }),
                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->prefix('₦')
                        ->disabled()
                        ->dehydrated(),
                ])
                ->columnSpanFull()
                ->required(),

            Forms\Components\Textarea::make('notes')
                ->maxLength(1000)
                ->columnSpanFull(),

            Forms\Components\TextInput::make('total_price')
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
                Tables\Columns\TextColumn::make('table_number')
                    ->label('Table')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('waiter.name')
                    ->label('Steward')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'cooking',
                        'success' => 'ready',
                        'gray' => 'served',
                    ]),

                Tables\Columns\TextColumn::make('total_price')
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('markCooking')
                        ->label('Mark Cooking')
                        ->visible(fn (Order $record): bool => in_array(strtolower((string) $record->status), ['pending'], true))
                        ->action(fn (Order $record): bool => $record->update(['status' => 'cooking']))
                        ->color('info'),
                    Tables\Actions\Action::make('markReady')
                        ->label('Mark Ready')
                        ->visible(fn (Order $record): bool => in_array(strtolower((string) $record->status), ['pending', 'cooking'], true))
                        ->action(fn (Order $record): bool => $record->update(['status' => 'ready']))
                        ->color('success'),
                ])
                    ->visible(fn (): bool => Auth::check() && Auth::user()->hasAnyRole(['kitchen', 'kitchen_manager', 'admin', 'super_admin'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (): bool => Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin'])),
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
            'kitchen' => Pages\KitchenView::route('/kitchen'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['orderItems.meal', 'waiter']);

        if (Auth::check() && Auth::user()->hasAnyRole(['kitchen', 'kitchen_manager'])) {
            $query->whereIn('status', ['pending', 'cooking']);
        }

        if (Auth::check() && Auth::user()->hasAnyRole(['steward', 'general_order_person'])) {
            $query->where('waiter_id', Auth::id());
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['steward', 'general_order_person', 'kitchen', 'kitchen_manager', 'admin', 'super_admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['steward', 'general_order_person', 'admin', 'super_admin']);
    }

    public static function canEdit($record): bool
    {
        if (!Auth::check()) {
            return false;
        }

        if (Auth::user()->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        if (Auth::user()->hasAnyRole(['steward', 'general_order_person'])) {
            return (int) $record->waiter_id === (int) Auth::id();
        }

        return Auth::user()->hasAnyRole(['kitchen', 'kitchen_manager']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }
}
