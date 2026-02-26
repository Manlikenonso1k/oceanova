<?php

namespace App\Filament\Resources\ProcurementResource\Pages;

use App\Filament\Resources\ProcurementResource;
use App\Models\Procurement;
use App\Services\InventoryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProcurement extends CreateRecord
{
    protected static string $resource = ProcurementResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var Procurement $procurement */
        $quantityReceived = (float) ($data['quantity_received'] ?? 0);
        $amountTotal = (float) ($data['unit_cost'] ?? 0);
        $unitPrice = array_key_exists('unit_price', $data) && $data['unit_price'] !== null && $data['unit_price'] !== ''
            ? (float) $data['unit_price']
            : null;

        if ($amountTotal <= 0 && $unitPrice !== null && $unitPrice > 0 && $quantityReceived > 0) {
            $amountTotal = $quantityReceived * $unitPrice;
        }

        $procurement = app(InventoryService::class)->stockIn(
            (int) $data['ingredient_id'],
            $quantityReceived,
            $amountTotal,
            (string) $data['supplier_name'],
            (string) $data['received_at'],
            (string) ($data['status'] ?? 'completed'),
            $unitPrice,
            $data['receipt_attachment'] ?? null,
            auth()->id(),
        );

        return $procurement;
    }
}
