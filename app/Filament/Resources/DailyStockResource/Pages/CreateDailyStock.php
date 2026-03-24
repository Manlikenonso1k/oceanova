<?php

namespace App\Filament\Resources\DailyStockResource\Pages;

use App\Filament\Resources\DailyStockResource;
use App\Models\DailyStock;
use App\Models\Ingredient;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyStock extends CreateRecord
{
    protected static string $resource = DailyStockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['ingredient_id'])) {
            $ingredient = Ingredient::find($data['ingredient_id']);
            if ($ingredient) {
                $data['item_name'] = $ingredient->name;
            }
        }

        $opening = (float) ($data['opening_stock'] ?? 0);
        $added = (float) ($data['added_stock'] ?? 0);
        $in = (float) ($data['trans_in'] ?? 0);
        $out = (float) ($data['trans_out'] ?? 0);
        $closing = (float) ($data['closing_stock'] ?? 0);

        $totals = DailyStock::calculateTotals($opening, $added, $in, $out, $closing);

        $data['total_stock'] = $totals['total_stock'];
        $data['sales'] = $totals['sales'];

        return $data;
    }
}
