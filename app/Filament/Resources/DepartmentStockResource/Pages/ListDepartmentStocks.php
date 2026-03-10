<?php

namespace App\Filament\Resources\DepartmentStockResource\Pages;

use App\Filament\Resources\DepartmentStockResource;
use Filament\Resources\Pages\ListRecords;

class ListDepartmentStocks extends ListRecords
{
    protected static string $resource = DepartmentStockResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
