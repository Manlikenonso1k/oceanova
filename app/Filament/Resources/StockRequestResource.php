<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockRequestResource\Pages;
use App\Models\DailyStock;
use App\Models\StockRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StockRequestResource extends Resource
{
    protected static ?string $model = StockRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    protected static ?string $navigationLabel = 'Stock Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('item_name')
                    ->label('Item')
                    ->options(function () {
                        $user = \Illuminate\Support\Facades\Auth::user();
                        $ingredientQuery = \App\Models\Ingredient::query();

                        if ($user && method_exists($user, 'hasAnyRole')) {
                            if ($user->hasAnyRole(['barman', 'bar_manager'])) {
                                // Prefer ingredients explicitly marked as Beverage, or those present in BarStockSheet
                                $ingredientQuery->where(function ($q) {
                                    $q->where('category', 'Beverage')
                                      ->orWhereHas('barStockSheets');
                                });
                            } elseif ($user->hasAnyRole(['chef', 'kitchen', 'kitchen_manager'])) {
                                // Prefer ingredients explicitly marked as Ingredient, or those present in DepartmentStock for Kitchen
                                $ingredientQuery->where(function ($q) {
                                    $q->where('category', 'Ingredient')
                                      ->orWhereHas('departmentStocks', function ($qs) {
                                          $qs->whereHas('department', function ($qd) {
                                              $qd->where('name', 'like', '%kitchen%');
                                          });
                                      });
                                });
                            }
                        }

                        return $ingredientQuery->orderBy('name')->pluck('name', 'name');
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->minValue(0.001)
                    ->required(),

                Forms\Components\Select::make('category')
                    ->options([
                        'Bar' => 'Bar',
                        'Kitchen' => 'Kitchen',
                    ])
                    ->default(fn (): string => static::defaultCategoryForCurrentUser())
                    ->disabled(fn (): bool => !static::isManagerUser())
                    ->dehydrated()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'declined' => 'Declined',
                    ])
                    ->default('pending')
                    ->disabled(fn (): bool => !static::isManagerUser())
                    ->dehydrated(),

                Forms\Components\Textarea::make('manager_notes')
                    ->rows(3)
                    ->disabled(fn (): bool => !static::isManagerUser()),

                Forms\Components\Hidden::make('requested_by')
                    ->default(fn (): ?int => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->badge()->sortable(),
                Tables\Columns\TextColumn::make('quantity')->numeric(decimalPlaces: 3)->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'declined',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('requester.name')
                    ->label('Requested By')
                    ->searchable(),
                Tables\Columns\TextColumn::make('processor.name')
                    ->label('Processed By')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('processed_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (StockRequest $record): bool => static::canProcess() && $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('manager_notes')
                            ->label('Manager Notes')
                            ->rows(3),
                    ])
                    ->action(function (StockRequest $record, array $data): void {
                        $record->update([
                            'status' => 'approved',
                            'manager_notes' => (string) ($data['manager_notes'] ?? ''),
                            'processed_by' => Auth::id(),
                            'processed_at' => now(),
                        ]);

                        static::applyApprovedQuantityToNextDay($record);

                        Notification::make()
                            ->title('Stock request approved.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('decline')
                    ->label('Decline')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (StockRequest $record): bool => static::canProcess() && $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('manager_notes')
                            ->label('Manager Notes')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (StockRequest $record, array $data): void {
                        $record->update([
                            'status' => 'declined',
                            'manager_notes' => (string) ($data['manager_notes'] ?? ''),
                            'processed_by' => Auth::id(),
                            'processed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Stock request declined.')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->visible(fn (): bool => static::isManagerUser()),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (StockRequest $record): bool => static::canDelete($record)),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockRequests::route('/'),
            'create' => Pages\CreateStockRequest::route('/create'),
            'edit' => Pages\EditStockRequest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['requester', 'processor']);

        if (!Auth::check()) {
            return $query->whereRaw('1 = 0');
        }

        if (static::isManagerUser()) {
            return $query;
        }

        return $query->where('requested_by', Auth::id())
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
        return Auth::check() && static::isManagerUser();
    }

    public static function canDelete($record): bool
    {
        if (!Auth::check() || !static::isManagerUser()) {
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
            return (bool) call_user_func([$user, 'hasPermissionTo'], 'delete stock_request')
                || (bool) call_user_func([$user, 'hasAnyRole'], ['admin', 'super_admin', 'general_manager']);
        } catch (\Throwable) {
            return true;
        }
    }

    public static function notifyManagersAboutRequest(StockRequest $request): void
    {
        $managers = User::query()
            ->whereIn('role', ['admin', 'super_admin', 'general_manager', 'bar_manager', 'kitchen_manager', 'manager'])
            ->get();

        if ($managers->isEmpty()) {
            return;
        }

        Notification::make()
            ->title('New stock request submitted')
            ->body("{$request->category}: {$request->item_name} x {$request->quantity}")
            ->sendToDatabase($managers);
    }

    private static function canProcess(): bool
    {
        return Auth::check() && static::isManagerUser();
    }

    private static function applyApprovedQuantityToNextDay(StockRequest $request): void
    {
        $stockDate = now()->addDay()->toDateString();

        $dailyStock = DailyStock::query()->firstOrCreate(
            [
                'item_name' => $request->item_name,
                'category' => $request->category,
                'stock_date' => $stockDate,
            ],
            [
                'price_ngn' => 0,
                'opening_stock' => 0,
                'added_stock' => 0,
                'trans_in' => 0,
                'trans_out' => 0,
                'closing_stock' => 0,
                'total_stock' => 0,
                'sales' => 0,
                'recorded_by' => $request->requested_by,
            ],
        );

        $dailyStock->added_stock = (float) $dailyStock->added_stock + (float) $request->quantity;

        $totals = DailyStock::calculateTotals(
            (float) $dailyStock->opening_stock,
            (float) $dailyStock->added_stock,
            (float) $dailyStock->trans_in,
            (float) $dailyStock->trans_out,
            (float) $dailyStock->closing_stock,
        );

        $dailyStock->total_stock = $totals['total_stock'];
        $dailyStock->sales = $totals['sales'];
        $dailyStock->save();
    }

    private static function isManagerUser(): bool
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
