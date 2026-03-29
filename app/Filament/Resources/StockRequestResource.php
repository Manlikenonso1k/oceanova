<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockRequestResource\Pages;
use App\Models\DailyStock;
use App\Models\StockRequest;
use App\Models\User;
use App\Models\Department;
use App\Models\DepartmentStock;
use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;
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
                Forms\Components\Select::make('ingredient_id')
                    ->label('Item')
                    ->options(function () {
                        $user = \Illuminate\Support\Facades\Auth::user();
                        $ingredientQuery = \App\Models\Ingredient::query();

                        if ($user && method_exists($user, 'hasAnyRole')) {
                            if ($user->hasAnyRole(['barman', 'bar_manager'])) {
                                $ingredientQuery->where(function ($q) {
                                    $q->where('category', 'Beverage')
                                      ->orWhereHas('barStockSheets');
                                });
                            } elseif ($user->hasAnyRole(['chef', 'kitchen', 'kitchen_manager'])) {
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

                        return $ingredientQuery->orderBy('name')->pluck('name', 'id');
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
                Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Item')
                    ->searchable()
                    ->sortable(),
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
                        DB::transaction(function () use ($record, $data): void {
                            // Resolve ingredient id
                            $ingredientId = $record->ingredient_id ?? null;
                            if (! $ingredientId && ! empty($record->item_name)) {
                                $ingredient = Ingredient::query()
                                    ->whereRaw('LOWER(name) = ?', [strtolower($record->item_name)])
                                    ->first();
                                $ingredientId = $ingredient?->id ?? null;
                            }

                            // Find Main Store department
                            $mainDeptId = Department::query()->where('is_main', true)->value('id');
                            if (! $mainDeptId) {
                                throw new \RuntimeException('Main Store department not configured.');
                            }

                            // Ensure source exists and lock it
                            $source = DepartmentStock::query()
                                ->where('department_id', $mainDeptId)
                                ->where('ingredient_id', $ingredientId)
                                ->lockForUpdate()
                                ->first();

                            if (! $source) {
                                $source = DepartmentStock::query()->create([
                                    'department_id' => $mainDeptId,
                                    'ingredient_id' => $ingredientId,
                                    'quantity' => 0,
                                ]);
                            }

                            if ((float) $source->quantity < (float) $record->quantity) {
                                throw new \RuntimeException('Insufficient Main Store stock to approve this request.');
                            }

                            // Subtract from main store
                            $source->decrement('quantity', (float) $record->quantity);

                            // Also decrement master Ingredient current_stock if present
                            if ($ingredientId) {
                                Ingredient::query()->where('id', $ingredientId)->decrement('current_stock', (float) $record->quantity);
                            }

                            // Determine destination department based on category
                            $toDeptCode = $record->category === 'Bar' ? 'BAR' : 'KITCHEN';
                            $toDeptId = Department::query()->where('code', $toDeptCode)->value('id');
                            if (! $toDeptId) {
                                $toDeptId = Department::query()->where('name', 'like', '%'.$record->category.'%')->value('id') ?: $mainDeptId;
                            }

                            // Upsert destination department stock and increment
                            $dest = DepartmentStock::query()
                                ->where('department_id', $toDeptId)
                                ->where('ingredient_id', $ingredientId)
                                ->lockForUpdate()
                                ->first();

                            if (! $dest) {
                                $dest = DepartmentStock::query()->create([
                                    'department_id' => $toDeptId,
                                    'ingredient_id' => $ingredientId,
                                    'quantity' => 0,
                                ]);
                            }

                            $dest->increment('quantity', (float) $record->quantity);

                            // Finally mark the request as approved
                            $record->update([
                                'status' => 'approved',
                                'manager_notes' => (string) ($data['manager_notes'] ?? ''),
                                'processed_by' => Auth::id(),
                                'processed_at' => now(),
                            ]);
                        });

                        // After successful transaction, update next day added_stock as before
                        static::applyApprovedQuantityToNextDay($record);

                        Notification::make()
                            ->title('Stock request approved and moved from Main Store.')
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
        return Auth::check() && method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin');
    }

    public static function notifyManagersAboutRequest(StockRequest $request): void
    {
        $managers = User::query()
            ->whereIn('role', ['admin', 'super_admin', 'general_manager', 'bar_manager', 'kitchen_manager', 'manager'])
            ->get();

        if ($managers->isEmpty()) {
            return;
        }

        $name = $request->ingredient?->name ?? $request->item_name;

        Notification::make()
            ->title('New stock request submitted')
            ->body("{$request->category}: {$name} x {$request->quantity}")
            ->sendToDatabase($managers);
    }

    private static function canProcess(): bool
    {
        return Auth::check() && static::isManagerUser();
    }

    private static function applyApprovedQuantityToNextDay(StockRequest $request): void
    {
        $stockDate = now()->addDay()->toDateString();

        $ingredientId = $request->ingredient_id ?? null;
        $itemName = $request->ingredient?->name ?? $request->item_name;

        $dailyStock = DailyStock::query()->firstOrCreate(
            [
                'ingredient_id' => $ingredientId,
                'item_name' => $itemName,
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
