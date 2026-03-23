<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyStockResource\Pages;
use App\Models\DailyStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DailyStockResource extends Resource
{
    protected static ?string $model = DailyStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    protected static ?string $navigationLabel = 'Daily Stocks';

    public static function form(Form $form): Form
    {
        $recalculate = function (Forms\Get $get, Forms\Set $set): void {
            $opening = (float) ($get('opening_stock') ?? 0);
            $added = (float) ($get('added_stock') ?? 0);
            $in = (float) ($get('trans_in') ?? 0);
            $out = (float) ($get('trans_out') ?? 0);
            $closing = (float) ($get('closing_stock') ?? 0);

            $totals = DailyStock::calculateTotals($opening, $added, $in, $out, $closing);

            $set('total_stock', $totals['total_stock']);
            $set('sales', $totals['sales']);
        };

        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('item_name')
                    ->label('Item Name')
                    ->options(function () {
                        $user = \Illuminate\Support\Facades\Auth::user();
                        $query = \App\Models\Ingredient::query();
                        if ($user && method_exists($user, 'hasAnyRole')) {
                            if ($user->hasAnyRole(['barman', 'bar_manager'])) {
                                $query->where('category', 'Bar');
                            } elseif ($user->hasAnyRole(['chef', 'kitchen', 'kitchen_manager'])) {
                                $query->where('category', 'Kitchen');
                            }
                        }
                        return $query->orderBy('name')->pluck('name', 'name');
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('category')
                    ->options([
                        'Bar' => 'Bar',
                        'Kitchen' => 'Kitchen',
                    ])
                    ->default(fn (): string => static::defaultCategoryForCurrentUser())
                    ->disabled(fn (): bool => !static::isPrivilegedUser())
                    ->dehydrated()
                    ->required(),

                Forms\Components\DatePicker::make('stock_date')
                    ->default(now())
                    ->required(),

                Forms\Components\TextInput::make('price_ngn')
                    ->label('Price (₦)')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->disabled(fn (): bool => !static::isPrivilegedUser())
                    ->dehydrated(),

                Forms\Components\TextInput::make('opening_stock')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated($recalculate)
                    ->required(),

                Forms\Components\TextInput::make('added_stock')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated($recalculate)
                    ->required(),

                Forms\Components\TextInput::make('trans_in')
                    ->label('Trans In')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated($recalculate)
                    ->required(),

                Forms\Components\TextInput::make('trans_out')
                    ->label('Trans Out')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated($recalculate)
                    ->required(),

                Forms\Components\TextInput::make('closing_stock')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated($recalculate)
                    ->required(),

                Forms\Components\TextInput::make('total_stock')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(),

                Forms\Components\TextInput::make('sales')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(),

                Forms\Components\Textarea::make('remarks')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('recorded_by')
                    ->default(fn (): ?int => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stock_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_ngn')
                    ->label('Price (₦)')
                    ->money('NGN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('opening_stock')->numeric(decimalPlaces: 3),
                Tables\Columns\TextColumn::make('added_stock')->numeric(decimalPlaces: 3),
                Tables\Columns\TextColumn::make('trans_in')->numeric(decimalPlaces: 3),
                Tables\Columns\TextColumn::make('trans_out')->numeric(decimalPlaces: 3),
                Tables\Columns\TextColumn::make('total_stock')->numeric(decimalPlaces: 3),
                Tables\Columns\TextColumn::make('sales')->numeric(decimalPlaces: 3),
                Tables\Columns\TextColumn::make('closing_stock')->numeric(decimalPlaces: 3),
                Tables\Columns\TextColumn::make('recorder.name')
                    ->label('Recorded By')
                    ->placeholder('System'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (): bool => static::isPrivilegedUser()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (DailyStock $record): bool => static::canDelete($record)),
            ])
            ->bulkActions([])
            ->defaultSort('stock_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyStocks::route('/'),
            'create' => Pages\CreateDailyStock::route('/create'),
            'edit' => Pages\EditDailyStock::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with('recorder');

        if (!Auth::check()) {
            return $query->whereRaw('1 = 0');
        }

        if (static::isPrivilegedUser()) {
            return $query;
        }

        return $query->where('recorded_by', Auth::id())
            ->where('category', static::defaultCategoryForCurrentUser());
    }

    public static function canViewAny(): bool
    {
        return static::userHasAnyRole([
            'barman',
            'chef',
            'kitchen',
            'admin',
            'super_admin',
            'general_manager',
            'bar_manager',
            'kitchen_manager',
            'manager',
        ]);
    }

    public static function canCreate(): bool
    {
        return self::canViewAny();
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && static::isPrivilegedUser();
    }

    public static function canDelete($record): bool
    {
        if (!Auth::check() || !static::isPrivilegedUser()) {
            return false;
        }

        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if (!method_exists($user, 'hasPermissionTo')) {
            return true;
        }

        try {
            return (bool) call_user_func([$user, 'hasPermissionTo'], 'delete daily_stock')
                || (bool) call_user_func([$user, 'hasAnyRole'], ['admin', 'super_admin', 'general_manager']);
        } catch (\Throwable) {
            return true;
        }
    }

    private static function isPrivilegedUser(): bool
    {
        return static::userHasAnyRole([
            'admin',
            'super_admin',
            'general_manager',
            'bar_manager',
            'kitchen_manager',
            'manager',
        ]);
    }

    private static function defaultCategoryForCurrentUser(): string
    {
        $user = Auth::user();

        if ($user && method_exists($user, 'hasAnyRole') && (bool) call_user_func([$user, 'hasAnyRole'], ['chef', 'kitchen', 'kitchen_manager'])) {
            return 'Kitchen';
        }

        return 'Bar';
    }

    private static function userHasAnyRole(array $roles): bool
    {
        $user = Auth::user();

        return $user
            && method_exists($user, 'hasAnyRole')
            && (bool) call_user_func([$user, 'hasAnyRole'], $roles);
    }
}
