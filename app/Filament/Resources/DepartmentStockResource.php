<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentStockResource\Pages;
use App\Models\Department;
use App\Models\DepartmentStock;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DepartmentStockResource extends Resource
{
    protected static ?string $model = DepartmentStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    protected static ?string $navigationLabel = 'Department Stock';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingredient.name')->label('Item')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('department.name')->label('Department')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('quantity')->numeric(decimalPlaces: 3)->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->since()->label('Updated'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (): bool => Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager', 'store_keeper'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (): bool => Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager'])),
            ])
            ->bulkActions([])
            ->defaultSort('ingredient.name');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['ingredient', 'department']);

        $user = Auth::user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasAnyRole(['admin', 'super_admin', 'general_manager'])) {
            return $query;
        }

        if ($user->hasRole('store_keeper')) {
            $mainStoreId = Department::query()->where('code', 'MAIN_STORE')->value('id');

            return $mainStoreId ? $query->where('department_id', $mainStoreId) : $query->whereRaw('1 = 0');
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
                $departmentId = Department::query()->where('code', $departmentCode)->value('id');

                return $departmentId ? $query->where('department_id', $departmentId) : $query->whereRaw('1 = 0');
            }
        }

        return $query->whereRaw('1 = 0');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartmentStocks::route('/'),
            'create' => Pages\CreateDepartmentStock::route('/create'),
            'edit' => Pages\EditDepartmentStock::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole([
            'admin', 'super_admin', 'general_manager', 'store_keeper',
            'kitchen_manager', 'bar_manager', 'morithos_manager', 'oceanova_manager', 'cleaning_lead', 'barman',
        ]);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager', 'store_keeper']);
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager', 'store_keeper']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager']);
    }
}
