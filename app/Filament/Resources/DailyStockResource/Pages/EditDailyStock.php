<?php

namespace App\Filament\Resources\DailyStockResource\Pages;

use App\Filament\Resources\DailyStockResource;
use App\Models\DailyStock;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyStock extends EditRecord
{
    protected static string $resource = DailyStockResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn (): bool => DailyStockResource::canDelete($this->record)),
        ];
    }
}
