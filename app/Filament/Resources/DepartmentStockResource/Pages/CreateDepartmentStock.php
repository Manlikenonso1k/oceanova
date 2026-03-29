<?php

namespace App\Filament\Resources\DepartmentStockResource\Pages;

use App\Filament\Resources\DepartmentStockResource;
use App\Models\Department;
use App\Models\Ingredient;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartmentStock extends CreateRecord
{
    protected static string $resource = DepartmentStockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('ingredient_id')
                ->label('Item')
                ->options(Ingredient::query()->orderBy('name')->pluck('name', 'id'))
                ->required(),

            Forms\Components\Select::make('department_id')
                ->label('Department')
                ->options(Department::query()->orderBy('name')->pluck('name', 'id'))
                ->required(),

            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->required(),
        ];
    }
}
