<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyStockResource\Pages;
use App\Models\DailyStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
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
                Forms\Components\Select::make('ingredient_id')
                    ->label('Item')
                    ->options(function () {
                        $user = \Illuminate\Support\Facades\Auth::user();
                        $query = \App\Models\Ingredient::query();
                        if ($user && method_exists($user, 'hasAnyRole')) {
                            if ($user->hasAnyRole(['barman', 'bar_manager'])) {
                                $query->where('category', 'Beverage');
                            } elseif ($user->hasAnyRole(['chef', 'kitchen', 'kitchen_manager'])) {
                                $query->where('category', 'Ingredient');
                            }
                        }
                        return $query->orderBy('name')->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set) use ($recalculate): void {
                        $ingredientId = $get('ingredient_id');
                        $category = $get('category') ?? (\App\Filament\Resources\DailyStockResource::defaultCategoryForCurrentUser());

                        if (! $ingredientId) {
                            $set('opening_stock', 0);
                            ($recalculate)($get, $set);
                            return;
                        }

                        // Resolve department id for this category (Bar/Kitchen)
                        $deptCode = $category === 'Bar' ? 'BAR' : 'KITCHEN';
                        $deptId = \App\Models\Department::query()->where('code', $deptCode)->value('id');
                        if (! $deptId) {
                            $deptId = \App\Models\Department::query()->where('name', 'like', "%{$category}%")->value('id');
                        }

                        // Try department stock first
                        $opening = 0;
                        if ($deptId) {
                            $deptStock = \App\Models\DepartmentStock::query()
                                ->where('department_id', $deptId)
                                ->where('ingredient_id', $ingredientId)
                                ->value('quantity');

                            if ($deptStock !== null) {
                                $opening = (float) $deptStock;
                            }
                        }

                        // Fallback to master ingredient current_stock
                        if ($opening === 0) {
                            $ingredient = \App\Models\Ingredient::find($ingredientId);
                            $opening = (float) ($ingredient->current_stock ?? 0);
                        }

                        $set('opening_stock', $opening);
                        ($recalculate)($get, $set);
                    }),

                Forms\Components\Select::make('category')
                    ->options([
                        'Bar' => 'Bar',
                        'Kitchen' => 'Kitchen',
                    ])
                    ->default(fn (): string => static::defaultCategoryForCurrentUser())
                    ->disabled(fn (): bool => !static::isPrivilegedUser())
                    ->dehydrated()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set) use ($recalculate): void {
                        // when category changes, refresh opening stock for selected ingredient
                        $ingredientId = $get('ingredient_id');
                        if (! $ingredientId) {
                            $set('opening_stock', 0);
                            ($recalculate)($get, $set);
                            return;
                        }

                        $deptCode = $get('category') === 'Bar' ? 'BAR' : 'KITCHEN';
                        $deptId = \App\Models\Department::query()->where('code', $deptCode)->value('id');
                        if (! $deptId) {
                            $cat = $get('category');
                            $deptId = \App\Models\Department::query()->where('name', 'like', "%{$cat}%")->value('id');
                        }

                        $opening = 0;
                        if ($deptId) {
                            $deptStock = \App\Models\DepartmentStock::query()
                                ->where('department_id', $deptId)
                                ->where('ingredient_id', $ingredientId)
                                ->value('quantity');

                            if ($deptStock !== null) {
                                $opening = (float) $deptStock;
                            }
                        }

                        if ($opening === 0) {
                            $ingredient = \App\Models\Ingredient::find($ingredientId);
                            $opening = (float) ($ingredient->current_stock ?? 0);
                        }

                        $set('opening_stock', $opening);
                        ($recalculate)($get, $set);
                    }),

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
                    ->default(fn (\Filament\Forms\Get $get) => (float) (\App\Models\Ingredient::find($get('ingredient_id'))->current_stock ?? 0))
                    ->live(onBlur: true)
                    ->afterStateUpdated($recalculate)
                    ->required(),

                Forms\Components\TextInput::make('added_stock')
                    ->numeric()
                    ->default(fn (\Filament\Forms\Get $get) => (float) (function () use ($get) {
                        $ingredientId = $get('ingredient_id');
                        if (! $ingredientId) {
                            return 0;
                        }

                        $ingredient = \App\Models\Ingredient::find($ingredientId);
                        if (! $ingredient) {
                            return 0;
                        }

                        // Find last daily stock for this ingredient
                        $last = \App\Models\DailyStock::query()
                            ->where('ingredient_id', $ingredientId)
                            ->orderBy('stock_date', 'desc')
                            ->first();

                        $since = $last ? $last->stock_date->toDateString() : null;

                        $query = \App\Models\StockRequest::query()
                            ->where('status', 'approved')
                            ->where('ingredient_id', $ingredientId);

                        if ($since) {
                            $query->where('processed_at', '>', $since);
                        }

                        return (float) $query->sum('quantity');
                    })())
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
                Forms\Components\Hidden::make('item_name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stock_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Item')
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
            ->filters([
                SelectFilter::make('category')
                    ->label('Department')
                    ->options([
                        'Bar' => 'Bar',
                        'Kitchen' => 'Kitchen',
                    ]),
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
        return Auth::check() && method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin');
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
