<?php

namespace App\Filament\Resources\DepartmentStockResource\Pages;

use App\Filament\Resources\DepartmentStockResource;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;

class EditDepartmentStock extends EditRecord
{
    protected static string $resource = DepartmentStockResource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->required(),
        ];
    }
}
