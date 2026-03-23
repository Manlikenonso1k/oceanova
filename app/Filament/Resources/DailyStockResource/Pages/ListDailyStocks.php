<?php

namespace App\Filament\Resources\DailyStockResource\Pages;

use App\Filament\Resources\DailyStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyStocks extends ListRecords
{
    protected static string $resource = DailyStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
