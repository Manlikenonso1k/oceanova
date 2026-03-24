<?php

namespace App\Filament\Resources\DailyStockResource\Pages;

use App\Filament\Resources\DailyStockResource;
use App\Models\DailyStock;
use App\Models\Ingredient;
use App\Models\StockRequest;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

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
        // Ensure numeric fields default to 0 when missing
        $data['opening_stock'] = (float) ($data['opening_stock'] ?? 0);
        $data['added_stock'] = (float) ($data['added_stock'] ?? 0);
        $data['trans_in'] = (float) ($data['trans_in'] ?? 0);
        $data['trans_out'] = (float) ($data['trans_out'] ?? 0);
        $data['closing_stock'] = (float) ($data['closing_stock'] ?? 0);

        $opening = (float) $data['opening_stock'];
        $added = (float) $data['added_stock'];
        $in = (float) ($data['trans_in'] ?? 0);
        $out = (float) ($data['trans_out'] ?? 0);
        $closing = (float) ($data['closing_stock'] ?? 0);

        $totals = DailyStock::calculateTotals($opening, $added, $in, $out, $closing);

        $data['total_stock'] = $totals['total_stock'];
        $data['sales'] = $totals['sales'];

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var DailyStock $record */
        $record = $this->record;

        if (! $record || ! $record->ingredient_id) {
            return;
        }

        DB::transaction(function () use ($record): void {
            $ingredient = Ingredient::find($record->ingredient_id);
            if (! $ingredient) {
                return;
            }

            // Opening stock should reflect master current_stock at creation
            $record->opening_stock = (float) $ingredient->current_stock;

            // Added stock: sum of approved requests since last daily stock
            $last = DailyStock::query()
                ->where('ingredient_id', $record->ingredient_id)
                ->where('id', '<>', $record->id)
                ->orderBy('stock_date', 'desc')
                ->first();

            $since = $last ? $last->stock_date->toDateString() : null;

            $query = StockRequest::query()
                ->where('status', 'approved')
                ->where('ingredient_id', $record->ingredient_id);

            if ($since) {
                $query->where('processed_at', '>', $since);
            }

            $record->added_stock = (float) $query->sum('quantity');

            // Recalculate totals and save
            $totals = DailyStock::calculateTotals(
                (float) $record->opening_stock,
                (float) $record->added_stock,
                (float) $record->trans_in,
                (float) $record->trans_out,
                (float) $record->closing_stock,
            );

            $record->total_stock = $totals['total_stock'];
            $record->sales = $totals['sales'];

            $record->save();
        });
    }
}
