<?php

namespace App\Filament\Resources\BarmanResource\Pages;

use App\Filament\Resources\BarmanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarmen extends ListRecords
{
    protected static string $resource = BarmanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
