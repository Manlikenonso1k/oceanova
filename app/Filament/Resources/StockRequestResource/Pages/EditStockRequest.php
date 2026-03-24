<?php

namespace App\Filament\Resources\StockRequestResource\Pages;

use App\Filament\Resources\StockRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Ingredient;

class EditStockRequest extends EditRecord
{
    protected static string $resource = StockRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn (): bool => StockRequestResource::canDelete($this->record)),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
