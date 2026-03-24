<?php

namespace App\Filament\Resources\StockRequestResource\Pages;

use App\Filament\Resources\StockRequestResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Ingredient;

class CreateStockRequest extends CreateRecord
{
    protected static string $resource = StockRequestResource::class;

    protected function afterCreate(): void
    {
        StockRequestResource::notifyManagersAboutRequest($this->record);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['ingredient_id'])) {
            $ingredient = Ingredient::find($data['ingredient_id']);
            if ($ingredient) {
                $data['item_name'] = $ingredient->name;
            }
        }

        return $data;
    }
}
