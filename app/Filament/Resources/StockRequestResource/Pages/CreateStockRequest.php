<?php

namespace App\Filament\Resources\StockRequestResource\Pages;

use App\Filament\Resources\StockRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockRequest extends CreateRecord
{
    protected static string $resource = StockRequestResource::class;

    protected function afterCreate(): void
    {
        StockRequestResource::notifyManagersAboutRequest($this->record);
    }
}
