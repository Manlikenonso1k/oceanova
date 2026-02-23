<?php

namespace App\Filament\Resources\BarmanResource\Pages;

use App\Filament\Resources\BarmanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBarman extends CreateRecord
{
    protected static string $resource = BarmanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category'] = 'Beverage';

        return $data;
    }
}
