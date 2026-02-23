<?php

namespace App\Filament\Resources\BarmanResource\Pages;

use App\Filament\Resources\BarmanResource;
use Filament\Resources\Pages\EditRecord;

class EditBarman extends EditRecord
{
    protected static string $resource = BarmanResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['category'] = 'Beverage';

        return $data;
    }
}
