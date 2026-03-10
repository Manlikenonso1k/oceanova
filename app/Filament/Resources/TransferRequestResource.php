<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferRequestResource\Pages;
use App\Models\Department;
use App\Models\DepartmentStock;
use App\Models\Ingredient;
use App\Models\StockTransferLog;
use App\Models\TransferRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferRequestResource extends Resource
{
    protected static ?string $model = TransferRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    protected static ?string $navigationLabel = 'Transfer Requests';

    public static function form(Form $form): Form
    {
        $mainStoreId = static::getMainStoreDepartmentId();

        return $form->schema([
            Forms\Components\Select::make('ingredient_id')
                ->label('Item')
                ->options(fn (): array => Ingredient::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->minValue(0.001)
                ->required(),

            Forms\Components\Hidden::make('from_department_id')
                ->default($mainStoreId),

            Forms\Components\Select::make('to_department_id')
                ->label('Requesting Department')
                ->options(function (): array {
                    $user = Auth::user();

                    if (!$user) {
                        return [];
                    }

                    if ($user->hasAnyRole(['admin', 'super_admin', 'general_manager', 'store_keeper'])) {
                        return Department::query()
                            ->where('code', '!=', 'MAIN_STORE')
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all();
                    }

                    $departmentId = static::resolveDepartmentIdForUser();

                    if (!$departmentId) {
                        return [];
                    }

                    return Department::query()->whereKey($departmentId)->pluck('name', 'id')->all();
                })
                ->default(fn (): ?int => static::resolveDepartmentIdForUser())
                ->disabled(fn (): bool => !(Auth::user()?->hasAnyRole(['admin', 'super_admin', 'general_manager', 'store_keeper']) ?? false))
                ->dehydrated()
                ->required(),

            Forms\Components\Textarea::make('notes')
                ->maxLength(1000)
                ->columnSpanFull(),

            Forms\Components\Hidden::make('requested_by')
                ->default(fn (): ?int => Auth::id()),

            Forms\Components\Hidden::make('status')
                ->default('pending'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('ingredient.name')->label('Item')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('quantity')->numeric(decimalPlaces: 3)->sortable(),
                Tables\Columns\TextColumn::make('fromDepartment.name')->label('From')->sortable(),
                Tables\Columns\TextColumn::make('toDepartment.name')->label('To')->sortable(),
                Tables\Columns\TextColumn::make('requester.name')->label('Requested By')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'declined',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('processed_at')->dateTime()->label('Processed At')->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('processRequest')
                    ->label('Process Request')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (TransferRequest $record): bool => static::canProcessRequest() && $record->status === 'pending')
                    ->form([
                        Forms\Components\Select::make('decision')
                            ->options([
                                'accepted' => 'Accept',
                                'declined' => 'Decline',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\Textarea::make('reason')
                            ->visible(fn (Forms\Get $get): bool => $get('decision') === 'declined')
                            ->required(fn (Forms\Get $get): bool => $get('decision') === 'declined'),
                    ])
                    ->action(function (TransferRequest $record, array $data): void {
                        $decision = (string) ($data['decision'] ?? '');

                        if ($decision === 'accepted') {
                            static::approveRequest($record);

                            Notification::make()
                                ->title('Transfer request accepted.')
                                ->success()
                                ->send();

                            return;
                        }

                        $record->update([
                            'status' => 'declined',
                            'reason' => (string) ($data['reason'] ?? 'Declined by Store Keeper'),
                            'processed_by' => Auth::id(),
                            'processed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Transfer request declined.')
                            ->warning()
                            ->send();
                    })
                    ->color('primary'),
                Tables\Actions\EditAction::make()
                    ->visible(fn (TransferRequest $record): bool => $record->status === 'pending' && (int) $record->requested_by === (int) Auth::id()),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function approveRequest(TransferRequest $record): void
    {
        DB::transaction(function () use ($record): void {
            /** @var DepartmentStock $source */
            $source = DepartmentStock::query()
                ->lockForUpdate()
                ->firstOrCreate(
                    [
                        'ingredient_id' => $record->ingredient_id,
                        'department_id' => $record->from_department_id,
                    ],
                    ['quantity' => 0]
                );

            if ((float) $source->quantity < (float) $record->quantity) {
                throw new \RuntimeException('Insufficient Main Store stock to approve this request.');
            }

            $source->decrement('quantity', (float) $record->quantity);

            /** @var DepartmentStock $destination */
            $destination = DepartmentStock::query()
                ->lockForUpdate()
                ->firstOrCreate(
                    [
                        'ingredient_id' => $record->ingredient_id,
                        'department_id' => $record->to_department_id,
                    ],
                    ['quantity' => 0]
                );

            $destination->increment('quantity', (float) $record->quantity);

            $record->update([
                'status' => 'accepted',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'reason' => null,
            ]);

            StockTransferLog::query()->create([
                'transfer_request_id' => $record->id,
                'ingredient_id' => $record->ingredient_id,
                'from_department_id' => $record->from_department_id,
                'to_department_id' => $record->to_department_id,
                'quantity' => $record->quantity,
                'movement_type' => 'transfer_out',
                'acted_by' => Auth::id(),
                'note' => 'Store transfer out',
            ]);

            StockTransferLog::query()->create([
                'transfer_request_id' => $record->id,
                'ingredient_id' => $record->ingredient_id,
                'from_department_id' => $record->from_department_id,
                'to_department_id' => $record->to_department_id,
                'quantity' => $record->quantity,
                'movement_type' => 'transfer_in',
                'acted_by' => Auth::id(),
                'note' => 'Department transfer in',
            ]);
        });
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['ingredient', 'fromDepartment', 'toDepartment', 'requester', 'processor']);

        $user = Auth::user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasAnyRole(['admin', 'super_admin', 'general_manager', 'store_keeper'])) {
            return $query;
        }

        $departmentId = static::resolveDepartmentIdForUser();

        if (!$departmentId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('to_department_id', $departmentId)
            ->where('requested_by', $user->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransferRequests::route('/'),
            'create' => Pages\CreateTransferRequest::route('/create'),
            'edit' => Pages\EditTransferRequest::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole([
            'admin', 'super_admin', 'general_manager', 'store_keeper',
            'kitchen_manager', 'bar_manager', 'morithos_manager', 'oceanova_manager', 'cleaning_lead',
        ]);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole([
            'admin', 'super_admin', 'general_manager',
            'kitchen_manager', 'bar_manager', 'morithos_manager', 'oceanova_manager', 'cleaning_lead',
        ]);
    }

    public static function canEdit($record): bool
    {
        return Auth::check()
            && (int) $record->requested_by === (int) Auth::id()
            && (string) $record->status === 'pending';
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    private static function canProcessRequest(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager', 'store_keeper']);
    }

    private static function getMainStoreDepartmentId(): ?int
    {
        return Department::query()->where('code', 'MAIN_STORE')->value('id');
    }

    private static function resolveDepartmentIdForUser(): ?int
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $roleToDepartmentCode = [
            'kitchen_manager' => 'KITCHEN',
            'bar_manager' => 'BAR',
            'morithos_manager' => 'MORITHOS',
            'oceanova_manager' => 'OCEANOVA',
            'cleaning_lead' => 'CLEANING',
            'barman' => 'BAR',
        ];

        foreach ($roleToDepartmentCode as $role => $departmentCode) {
            if ($user->hasRole($role)) {
                return Department::query()->where('code', $departmentCode)->value('id');
            }
        }

        return null;
    }
}
