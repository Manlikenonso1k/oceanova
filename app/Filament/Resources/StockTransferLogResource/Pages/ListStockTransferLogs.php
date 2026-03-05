<?php

namespace App\Filament\Resources\StockTransferLogResource\Pages;

use App\Filament\Resources\StockTransferLogResource;
use Filament\Resources\Pages\ListRecords;

class ListStockTransferLogs extends ListRecords
{
    protected static string $resource = StockTransferLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
